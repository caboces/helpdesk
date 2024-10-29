<?php

namespace app\controllers;

use Yii;
use DateTime;
use yii\db\Query;
use app\models\User;
use app\models\Ticket;
use app\models\JobType;
use yii\web\Controller;
use app\models\Building;
use app\models\District;
use app\models\Division;
use app\models\JobStatus;
use app\models\TimeEntry;
use app\models\Department;
use app\models\JobCategory;
use app\models\JobPriority;
use yii\filters\VerbFilter;
use app\models\CustomerType;
use app\models\TicketSearch;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use app\models\JobTypeCategory;
use app\models\TimeEntrySearch;
use app\models\DistrictBuilding;
use app\models\DepartmentBuilding;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use app\models\TechTicketAssignment;
use app\models\TicketAssignmentSearch;
use app\models\TicketClosedResolvedSearch;
use app\models\TicketRecentlyDeletedSearch;

/**
 * TicketController implements the CRUD actions for Ticket model.
 */
class TicketController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Ticket models.
     *
     * @return string
     */
    public function actionIndex()
    {
        // search assigned tickets
        $ticketAssignmentSearchModel = new TicketAssignmentSearch();
        $ticketAssignmentDataProvider = $ticketAssignmentSearchModel->search($this->request->queryParams);
        // search all tickets
        $searchModel = new TicketSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        // search closed / resolved tickets
        $ticketClosedResolvedSearchModel = new TicketClosedResolvedSearch();
        $ticketClosedResolvedDataProvider = $ticketClosedResolvedSearchModel->search($this->request->queryParams);
        // search recently deleted ticketd
        $ticketRecentlyDeletedSearchModel = new TicketRecentlyDeletedSearch();
        $ticketRecentlyDeletedDataProvider = $ticketRecentlyDeletedSearchModel->search($this->request->queryParams);
        // search ticket tags
        $categories = ArrayHelper::map(JobCategory::getCategories(), 'name', 'name');
        $priorities = ArrayHelper::map(JobPriority::getPriorities(), 'name', 'name');
        $statuses = ArrayHelper::map(JobStatus::getStatuses(), 'name', 'name');
        $nonSelectableStatuses = ArrayHelper::map(JobStatus::getNonSelectableStatuses(), 'name', 'name');
        $types = ArrayHelper::map(JobType::getTypes(), 'name', 'name');
        // search customers
        $customerTypes = ArrayHelper::map(CustomerType::getCustomerTypes(), 'name', 'name');
        $districts = ArrayHelper::map(District::getDistricts(), 'name', 'name');
        $departments = ArrayHelper::map(Department::getDepartments(), 'name', 'name');
        $divisions = ArrayHelper::map(Division::getDivisions(), 'name', 'name');
        $buildings = ArrayHelper::map(Building::getBuildings(), 'name', 'name');

        $this->layout = 'blank';
        return $this->render('index', [
            // search assigned tickets
            'ticketAssignmentSearchModel' => $ticketAssignmentSearchModel,
            'ticketAssignmentDataProvider' => $ticketAssignmentDataProvider,
            // search all tickets
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            // search closed / resolved tickets
            'ticketClosedResolvedSearchModel' => $ticketClosedResolvedSearchModel,
            'ticketClosedResolvedDataProvider' => $ticketClosedResolvedDataProvider,
            // search recently deleted tickets
            'ticketRecentlyDeletedSearchModel' => $ticketRecentlyDeletedSearchModel,
            'ticketRecentlyDeletedDataProvider' => $ticketRecentlyDeletedDataProvider,
            // ticket tags
            'categories' => $categories,
            'priorities' => $priorities,
            'statuses' => $statuses,
            'nonSelectableStatuses' => $nonSelectableStatuses,      // needed for resolved/closed/billed ticket forms
            'types' => $types,
            // customers
            'customerTypes' => $customerTypes,
            'districts' => $districts,
            'departments' => $departments,
            'divisions' => $divisions,
            'buildings' => $buildings,
        ]);
    }

    /**
     * Displays a single Ticket model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Ticket model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Ticket();

        // search tech time entries for ticket
        $techTimeEntrySearch = new TimeEntrySearch();
        $techTimeEntryDataProvider = $techTimeEntrySearch->search(Yii::$app->request->get());

        // ticket tags
        $categories = ArrayHelper::map(JobCategory::getCategories(), 'id', 'name');
        $priorities = ArrayHelper::map(JobPriority::getPriorities(), 'id', 'name');
        $statuses = ArrayHelper::map(JobStatus::getStatuses(), 'id', 'name');
        $types = ArrayHelper::map(JobType::getTypes(), 'id', 'name');
        $jobTypeCategoryData = JobTypeCategory::getJobCategoryNamesFromJobTypeId($model);
        // customers
        $customerTypes = ArrayHelper::map(CustomerType::getCustomerTypes(), 'id', 'name');
        $districts = ArrayHelper::map(District::getDistricts(), 'id', 'name');
        // tack the corresponding division name onto department options
        $departments = ArrayHelper::map(Department::getSortedDepartments(), 'id', function($model) {return Division::findOne($model['division_id'])->name . ' > ' . $model['name'];});
        $divisions = ArrayHelper::map(Division::getDivisions(), 'id', 'name');
        $buildings = ArrayHelper::map(Building::getBuildings(), 'id', 'name');
        // customer buildings: this data will populate the ddl's based on dept/dist on load
        $departmentBuildingData = DepartmentBuilding::getBuildingNamesFromDepartmentId($model);
        $districtBuildingData = DistrictBuilding::getBuildingNamesFromDistrictId($model);
        // users
        $assignedTechData = TechTicketAssignment::getTechNamesFromTicketId($model);
        $users = ArrayHelper::map(User::getUsers(), 'id', 'username');

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {

                if (!empty($_POST['Ticket']['users'])) {
                    foreach ($_POST['Ticket']['users'] as $user_id) {
                        // Find user objects by user ids sent in POST
                        $user = User::findOne($user_id);
                        // Link users to ticket, this will create a record in TechTicketAssignment table for each selected user
                        $model->link('users', $user);
                    }
                }

                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        $this->layout = 'blank-container';

        return $this->render('create', [
            'model' => $model,
            // search time entries
            'techTimeEntrySearch' => $techTimeEntrySearch,
            'techTimeEntryDataProvider' => $techTimeEntryDataProvider,
            // ticket tags
            'categories' => $categories,
            'priorities' => $priorities,
            'statuses' => $statuses,
            'types' => $types,
            'jobTypeCategoryData' => $jobTypeCategoryData,
            // customers
            'customerTypes' => $customerTypes,
            'districts' => $districts,
            'departments' => $departments,
            'divisions' => $divisions,
            'buildings' => $buildings,
            // customer buildings
            'departmentBuildingData' => $departmentBuildingData,
            'districtBuildingData' => $districtBuildingData,
            //users
            'users' => $users,
            'assignedTechData' => $assignedTechData,
        ]);
    }

    /**
     * Updates an existing Ticket model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->user->can('update-ticket')) {
            Yii::$app->cache->flush();
            $model = $this->findModel($id);
            if ($model->status == '10') {

                // search tech time entries for ticket
                $techTimeEntrySearch = new TimeEntrySearch();
                $techTimeEntrySearch->ticket_id = $model->id;
                $techTimeEntryDataProvider = $techTimeEntrySearch->search(Yii::$app->request->get());

                // ticket tags
                $categories = ArrayHelper::map(JobCategory::getCategories(), 'id', 'name');
                $priorities = ArrayHelper::map(JobPriority::getPriorities(), 'id', 'name');
                $statuses = ArrayHelper::map(JobStatus::getStatuses(), 'id', 'name');
                $nonSelectableStatuses = ArrayHelper::map(JobStatus::getNonSelectableStatuses(), 'id', 'name');
                $types = ArrayHelper::map(JobType::getTypes(), 'id', 'name');
                $jobTypeCategoryData = JobTypeCategory::getJobCategoryNamesFromJobTypeId($model);
                // customers
                $customerTypes = ArrayHelper::map(CustomerType::getCustomerTypes(), 'id', 'code');
                // tack the corresponding division name onto department options
                $departments = ArrayHelper::map(Department::getSortedDepartments(), 'id', function($model) {return Division::findOne($model['division_id'])->name . ' > ' . $model['name'];});
                $districts = ArrayHelper::map(District::getDistricts(), 'id', 'name');
                $divisions = ArrayHelper::map(Division::getDivisions(), 'id', 'name');
                $buildings = ArrayHelper::map(Building::getBuildings(), 'id', 'name');
                // customer buildings
                $departmentBuildingData = DepartmentBuilding::getBuildingNamesFromDepartmentId($model);
                $districtBuildingData = DistrictBuilding::getBuildingNamesFromDistrictId($model);
                // users
                $users = ArrayHelper::map(User::getUsers(), 'id', 'username');
                $assignedTechData = TechTicketAssignment::getTechNamesFromTicketId($model);

                if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {

                    // To update the tech_ticket_assignment junction table
                    // This is kind of a lazy way of doing this, probably should check to see if any records need to be deleted instead of always doing it, but I'm keeping it for now :)
                    // First delete existing tech assignments in the table (otherwise it will not touch removed techs if you remove some but not all from a list)
                    foreach ($model->users as $user) {
                        // pass "true" because we want to delete the records, not just nullify all columns
                        $model->unlink('users', $user, true);
                    }
                    // If the selection is empty, we are done. If there is some data, insert a row into the tech_ticket_assignment table for each selected tech
                    if (!empty($_POST['Ticket']['users'])) {
                        foreach ($_POST['Ticket']['users'] as $user_id) {
                            $user = User::findOne($user_id);
                            $model->link('users', $user);
                        }
                    }

                    echo '<script>console.log("test2")</script>';

                    // TODO: add tech note space
                    // $model->link('activities', Yii::$app->user->identity);

                    return $this->redirect(['view', 'id' => $model->id]);
                }

                return $this->render('update', [
                    'model' => $model,
                    // search time entries
                    'techTimeEntrySearch' => $techTimeEntrySearch,
                    'techTimeEntryDataProvider' => $techTimeEntryDataProvider,
                    // ticket tags
                    'categories' => $categories,
                    'priorities' => $priorities,
                    'statuses' => $statuses,
                    'nonSelectableStatuses' => $nonSelectableStatuses,      // needed for resolved/closed/billed ticket forms
                    'types' => $types,
                    'jobTypeCategoryData' => $jobTypeCategoryData,
                    // customers
                    'customerTypes' => $customerTypes,
                    'departments' => $departments,
                    'districts' => $districts,
                    'divisions' => $divisions,
                    'buildings' => $buildings,
                    // customer buildings
                    'departmentBuildingData' => $departmentBuildingData,
                    'districtBuildingData' => $districtBuildingData,
                    // users
                    'assignedTechData' => $assignedTechData,
                    'users' => $users,
                ]);
            } else {
                // ticket isn't active!
                throw new ForbiddenHttpException('This ticket is not in your current workflow and cannot be edited. It has likely been marked for deletion, but can be added back to your workflow by an admin/supervisor.');
            }
        } else {
            // wrong permissions!
            throw new ForbiddenHttpException('You do not have permission to edit tickets.');
        }
    }


     /**
     * Soft-deletes an existing Ticket model.
     * If soft-delete is successful, the browser will be redirected to the 'index' page.
     * 
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionSoftDelete($id)
    {
        // must have the delete-ticket permission
        if (Yii::$app->user->can('soft-delete-ticket')) {
            $model = $this->findModel($id);
            $model->status = '9';
            $model->soft_deleted_by_user_id = Yii::$app->user->id;
            $model->soft_deletion_timestamp = (new DateTime())->format('Y-m-d H:i:s');
            $model->save();
            return $this->redirect(['index']);
        } else {
            // wrong permissions!
            throw new ForbiddenHttpException('You do not have permission to delete tickets.');
        }
    }

    /**
     * Deletes an existing Ticket model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * 
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        // must have the delete-ticket permission
        if (Yii::$app->user->can('delete-ticket')) {
            $this->findModel($id)->delete();
            return $this->redirect(['index']);
        } else {
            // wrong permissions!
            throw new ForbiddenHttpException('You do not have permission to permanently delete tickets.');
        }
    }

    /**
     * Resolves the current ticket.
     * If the user has permissions to resolve the ticket, it will be resolved and need
     * supervisor approval to be closed/billed.
     */
    public function actionResolve($id) {
        // if the user is allowed to resolve tickets, move forward
        if (Yii::$app->user->can('resolve-ticket')) {
            $model = $this->findModel($id);

            // set job status to resolved
            $model->job_status_id = 14;
            
            // save changes and go to the ticket view
            if ($this->request->isPost && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                // something else went wrong idk.
                throw new ForbiddenHttpException('Something went wrong. Please try again.');
            }
        } else {
            // wrong permissions!
            throw new ForbiddenHttpException('You do not have permission to resolve tickets.');
        }
    }

    /**
     * Closes the current ticket.
     * If the user has permissions to close the ticket, it will be closed and is eligible to be
     * billed.
     * After the ticket is billed, it will NOT be eligible for re-opening
     */
    public function actionClose($id) {
        // if the user is allowed to close tickets, move forward
        if (Yii::$app->user->can('close-ticket')) {
            $model = $this->findModel($id);

            // set job status to closed
            $model->job_status_id = 15;

            // save changes and go to the ticket view
            if ($this->request->isPost && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                // something else went wrong idk.
                throw new ForbiddenHttpException('Something went wrong. Please try again.');
            }
        } else {
            // wrong permissions!
            throw new ForbiddenHttpException('You do not have permission to close tickets.');
        }
    }

    /**
     * Closes the current ticket.
     * If the user has permissions to close the ticket, it will be closed and is eligible to be
     * billed.
     * After the ticket is billed, it will NOT be eligible for re-opening
     */
    public function actionReopen($id) {
        // if the user is allowed to close tickets AND the ticket has not been billed, move forward
        if (Yii::$app->user->can('reopen-ticket')) {
            $model = $this->findModel($id);

            // if the ticket has been billed, do not reopen.
            if ($model->job_status_id == 17) {
                throw new ForbiddenHttpException('This ticket has already been billed and cannot be reopened. Please create a new ticket.');
            } else {
                // set job status to open
                $model->job_status_id = 9;
            }

            // save changes and go to the ticket view
            if ($this->request->isPost && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                // something else went wrong idk.
                throw new ForbiddenHttpException('Something went wrong. Please try again.');
            }
        } else {
            // wrong permissions!
            throw new ForbiddenHttpException('You do not have permission to reopen tickets.');
        }
    }

    /**
     * Finds the Ticket model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Ticket the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ticket::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Dependent dropdown query for DepartmentBuilding. Checks for change
     */
    public function actionDepartmentBuildingDependentDropdownQuery()
    {
            $search_reference = Yii::$app->request->post('department_search_reference');
            $query = new Query;
            $query->select(['department_building.id', 'department_building.department_id', 'department_building.building_id', 'building.name', 'department.division_id'])
                    ->from('department_building')
                    ->innerJoin('building', 'department_building.building_id = building.id')
                    ->innerJoin('department', 'department_building.department_id = department.id')
                    ->where(['department_id' => $search_reference])
                    ->orderBy('name ASC');
            $rows = $query->all();

            $data = [];
            if (!empty($rows)) {
                foreach ($rows as $row) {
                    $data[] = ['id' => $row['id'], 'name' => $row['name'], 'division' => $row['division_id']];
                }
            } else {
                $data = '';
            }

            return $this->asJson($data);
    }

    /**
     * Dependent dropdown query for DistrictBuilding. Checks for change
     */
    public function actionDistrictBuildingDependentDropdownQuery()
    {
            $search_reference = Yii::$app->request->post('district_search_reference');
            $query = new Query;
            $query->select(['district_building.id', 'district_building.district_id', 'district_building.building_id', 'building.name'])
                    ->from('district_building')
                    ->innerJoin('building', 'district_building.building_id = building.id')
                    ->where(['district_id' => $search_reference])
                    ->orderBy('name ASC');
            $rows = $query->all();

            $data = [];
            if (!empty($rows)) {
                foreach ($rows as $row) {
                    $data[] = ['id' => $row['id'], 'name' => $row['name']];
                }
            } else {
                $data = '';
            }

            return $this->asJson($data);
    }

    /**
     * Populate the ticket categories DDL based on selected job type id
     * Can probably remove the job type naming and stuff, think i only need category stuff. not sure what ill do yet
     */
    public function actionJobCategoryDependentDropdownQuery() {
        $search_reference = Yii::$app->request->post('job_category_search_reference');
            $query = new Query;
            $query->select(['job_type_category.id', 'job_type_category.job_type_id', 'job_type_category.job_category_id', 'job_category.name'])
                    ->from('job_type_category')
                    ->innerJoin('job_category', 'job_type_category.job_category_id = job_category.id')
                    ->where(['job_type_id' => $search_reference])
                    ->orderBy('name ASC');
            $rows = $query->all();

            $data = [];
            if (!empty($rows)) {
                foreach ($rows as $row) {
                    $data[] = ['id' => $row['job_category_id'], 'name' => $row['name']];
                }
            } else {
                $data = '';
            }

            return $this->asJson($data);
    }

    /**
     * Return an array of tech time entries for the given ticket model, used to show 
     * current time entries in ticket form
     */
    private function queryTechTimeEntryData($id) {
        return TimeEntry::find()
            ->select([
                'time_entry.id', 
                'tech_time', 
                'overtime', 
                'travel_time', 
                'itinerate_time', 
                'entry_date', 
                'user_id', 
                'ticket_id', 
                'user.username'
            ])
            ->innerJoin('user', 'time_entry.user_id = user.id')
            ->where(['ticket_id' => $id])
            ->limit(100)
            ->asArray()
            ->all();
    }
}