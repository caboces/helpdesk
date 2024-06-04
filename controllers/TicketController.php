<?php

namespace app\controllers;

use Yii;
use yii\db\Query;
use app\models\User;
use app\models\Ticket;
use app\models\JobType;
use yii\web\Controller;
use app\models\Building;
use app\models\District;
use app\models\Division;
use app\models\JobStatus;
use app\models\Department;
use app\models\JobCategory;
use app\models\JobPriority;
use yii\filters\VerbFilter;
use app\models\CustomerType;
use app\models\TicketSearch;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use app\models\DistrictBuilding;
use app\models\DepartmentBuilding;
use app\models\TechTicketAssignment;
use app\models\TicketAssignmentSearch;
use app\models\TimeEntry;
use app\models\TimeEntrySearch;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;

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
        $ticketAssignmentSearch = new TicketAssignmentSearch();
        $ticketAssignmentDataProvider = $ticketAssignmentSearch->search(Yii::$app->request->get());
        // search all tickets
        $ticketSearch = new TicketSearch();
        $dataProvider = $ticketSearch->search(Yii::$app->request->get());
        // search ticket tags
        $categories = ArrayHelper::map(JobCategory::getCategories(), 'name', 'name');
        $priorities = ArrayHelper::map(JobPriority::getPriorities(), 'name', 'name');
        $statuses = ArrayHelper::map(JobStatus::getStatuses(), 'name', 'name');
        $types = ArrayHelper::map(JobType::getTypes(), 'name', 'name');
        // search customers
        $customerTypes = ArrayHelper::map(CustomerType::getCustomerTypes(), 'name', 'name');
        $districts = ArrayHelper::map(District::getDistricts(), 'name', 'name');
        $departments = ArrayHelper::map(Department::getDepartments(), 'name', 'name');
        $divisions = ArrayHelper::map(Division::getDivisions(), 'name', 'name');
        $buildings = ArrayHelper::map(Building::getBuildings(), 'name', 'name');

        $this->layout = 'blank-container';
        return $this->render('index', [
            // search assigned tickets
            'ticketAssignmentSearch' => $ticketAssignmentSearch,
            'ticketAssignmentDataProvider' => $ticketAssignmentDataProvider,
            // search all tickets
            'searchModel' => $ticketSearch,
            'dataProvider' => $dataProvider,
            // ticket tags
            'categories' => $categories,
            'priorities' => $priorities,
            'statuses' => $statuses,
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
        Yii::$app->cache->flush();
        $model = $this->findModel($id);

        // search tech time entries for ticket
        $techTimeEntrySearch = new TimeEntrySearch();
        $techTimeEntrySearch->ticket_id = $model->id;
        $techTimeEntryDataProvider = $techTimeEntrySearch->search(Yii::$app->request->get());

        // ticket tags
        $categories = ArrayHelper::map(JobCategory::getCategories(), 'id', 'name');
        $priorities = ArrayHelper::map(JobPriority::getPriorities(), 'id', 'name');
        $statuses = ArrayHelper::map(JobStatus::getStatuses(), 'id', 'name');
        $types = ArrayHelper::map(JobType::getTypes(), 'id', 'name');
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
            'types' => $types,
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
    }

    /**
     * Deletes an existing Ticket model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (Yii::$app->user->can('delete-ticket')) {
            $this->findModel($id)->delete();

            return $this->redirect(['index']);
        } else {
            throw new ForbiddenHttpException('You do not have permission to delete this ticket.');
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


        // throw this whole thing out and try again
        // $query = (new \yii\db\Query())
        // ->select([
        //     'time_entry.id', 
        //     'tech_time', 
        //     'overtime', 
        //     'travel_time', 
        //     'itinerate_time', 
        //     'entry_date', 
        //     'user_id', 
        //     'ticket_id', 
        //     'user.username'
        // ])
        // ->from(['time_entry'])
        // ->innerJoin('user', 'time_entry.user_id = user.id')
        // ->where(['ticket_id' => $model->id]);
}