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
use app\models\Part;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use app\models\TechTicketAssignment;
use app\models\TicketAssignmentSearch;
use app\models\TicketClosedResolvedSearch;
use app\models\Asset;
use app\models\TicketDraft;
use app\models\TicketNote;
use app\models\TicketRecentlyDeletedSearch;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\helpers\Url;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

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
        
        // get ticket drafts for the ticket queue
        $ticketDraftsDataProvider = new ActiveDataProvider([
            'query' => TicketDraft::find()
                ->where('ticket_draft.frozen != 1')
                ->orderBy('ticket_draft.created'),
            'sort' => [
                'defaultOrder' => [
                    'created' => SORT_ASC
                ]
            ]
        ]);

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
            'ticketDraftsDataProvider' => $ticketDraftsDataProvider,
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
        $model->created_by = Yii::$app->user->id;

        // if ticketDraftId was queried, then fill in some fields with that.
        $ticketDraftId = Yii::$app->getRequest()->getQueryParam('ticketDraftId');

        $ticketDraft = null;
        // get the ticket draft model if the id was provided to the controller
        if ($ticketDraftId) {
            $ticketDraft = TicketDraft::find()
                ->select(['customer_type_id', 
                    'division_id', 
                    'department_id', 
                    'department_building_id', 
                    'district_id', 
                    'district_building_id', 
                    'requestor',
                    'location',
                    'job_type_id',
                    'job_category_id',
                    'summary',
                    'description',
                    'email',
                    'phone'])
                ->where("ticket_draft.frozen = 0")
                ->where("ticket_draft.id = {$ticketDraftId}")
                ->one()
                ->toArray();
        }

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
        $assignedTechData = TechTicketAssignment::getTechNamesFromTicketId($model->id);
        $users = ArrayHelper::map(User::getUsers(), 'id', 'username');
        if ($this->request->isPost) {
            // if validation fails, this will not work
            if ($model->load($this->request->post()) && $model->save(false)) {
                // manually set the created_by to the user's id.
                $model->created_by = Yii::$app->user->id;
                if (!empty($_POST['Ticket']['users'])) {
                    foreach ($_POST['Ticket']['users'] as $user_id) {
                        // Find user objects by user ids sent in POST
                        $user = User::findOne($user_id);
                        // Link users to ticket, this will create a record in TechTicketAssignment table for each selected user
                        $model->link('users', $user);
                    }
                }

                // if this ticket was created with a Ticket Draft, then set that ticket draft's "frozen" value to 1 (true).
                if ($ticketDraftId) {
                    TicketDraft::find()->where(['id' => $ticketDraftId])->one()->freeze();
                }

                // TODO later. So you can create assets,parts,time entries, and notes in the ticket creation rather than update.
                // each thing below needs to store a "draft" version of the model, that is, no call to /*/create is made (* being 'asset', 'part', or 'time-entry' or else no ticket_id exists)
                // create each asset record

                // create each parts record

                // create each time_entry record

                return $this->redirect(['/ticket/update', 'id' => $model->id]);
            } else {
                // make a better ticket creation failure?
                throw new ServerErrorHttpException('A problem occured while trying to create the ticket.');
            }
        } else {
            $model->loadDefaultValues();
        } 

        $assetGridProps = $this->getTicketAssetGridProperties($model);
        $partsGridProps = $this->getTicketPartsGridProperties($model);
        $ticketNoteGridProps = $this->getTicketNotesGridProperties($model);

        $this->layout = 'blank-container';
        return $this->render('create', [
            'model' => $model,
            'partModel' => new Part(),
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
            'assetProvider' => $assetGridProps['provider'],
            'assetColumns' => $assetGridProps['columns'],
            'partsProvider' => $partsGridProps['provider'],
            'partsColumns' => $partsGridProps['columns'],
            'ticketNotesProvider' => $ticketNoteGridProps['provider'],
            'ticketNotesColumns' => $ticketNoteGridProps['columns'],
            'ticketDraft' => json_encode($ticketDraft),
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
                $assignedTechData = TechTicketAssignment::getTechNamesFromTicketId($model->id);

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

                    return $this->redirect(['update', 'id' => $model->id]);
                } 

                $assetGridProps = $this->getTicketAssetGridProperties($model);
                $partsGridProps = $this->getTicketPartsGridProperties($model);
                $ticketNoteGridProps = $this->getTicketNotesGridProperties($model);

                return $this->render('update', [
                    'model' => $model,
                    'partModel' => new Part(),
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
                    'assetProvider' => $assetGridProps['provider'],
                    'assetColumns' => $assetGridProps['columns'],
                    'partsProvider' => $partsGridProps['provider'],
                    'partsColumns' => $partsGridProps['columns'],
                    'ticketNotesProvider' => $ticketNoteGridProps['provider'],
                    'ticketNotesColumns' => $ticketNoteGridProps['columns'],
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
        // must have the soft-delete-ticket permission
        if (Yii::$app->user->can('soft-delete-ticket')) {
            $model = $this->findModel($id);
            $model->status = '9';
            $model->soft_deleted_by_user_id = Yii::$app->user->id;
            $model->soft_deletion_timestamp = (new DateTime())->format('Y-m-d H:i:s');
            $model->save();
            return $this->redirect(['index']);
        } else {
            // wrong permissions!
            throw new ForbiddenHttpException('You do not have permission to mark tickets for deletion.');
        }
    }

    /**
     * Undo soft-deletion on an existing Ticket model.
     * If undoing the soft-delete is successful, the browser will be redirected to the 'index' page.
     * 
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUndoSoftDelete($id)
    {
        // must have the undo-soft-delete-ticket permission
        if (Yii::$app->user->can('undo-soft-delete-ticket')) {
            $model = $this->findModel($id);
            $model->status = '10';
            $model->soft_deleted_by_user_id = NULL;
            $model->soft_deletion_timestamp = NULL;
            $model->save();
            return $this->redirect(['index']);
        } else {
            // wrong permissions!
            throw new ForbiddenHttpException('You do not have permission to return tickets marked for deletion to the workflow.');
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

            // job status updated; send emails
            $this->sendTicketStatusEmails($model);
            
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

            // job status updated; send emails
            $this->sendTicketStatusEmails($model);

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

                // job status updated; send emails
                $this->sendTicketStatusEmails($model);
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
     * Get the current primary tech of this ticket, if it exists
     */
    public function actionGetPrimaryTech() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $ticket_id = Yii::$app->request->get('ticket_id');
        $primaryTech = Ticket::find()->where("id = $ticket_id")->one()->getPrimaryTech()->select([
            'user.id',
            'user.username',
            'user.fname',
            'user.lname',
            'user.email'
            ])->one();

        return ['primaryTech' => $primaryTech];
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

    /**
     * Sends an email about a ticket's status to the assigned techs of that ticket
     *
     * @param ticketModel The ticket
     * @return bool whether the email was sent
     */
    public function sendTicketStatusEmails($ticketModel) {
        // string of user emails
        $recipients = TechTicketAssignment::getNamesAndEmailsFromTicket($ticketModel->id);
        // get job labels
        $jobLabels = Ticket::getJobLabels($ticketModel->id);
        // get primary tech name
        $primaryTechName = User::findOne($ticketModel->primary_tech_id)->getUsername();
        // create Yii email object array
        $emails = [];
        if (!empty($recipients)) {
            foreach ((array) $recipients as $recip) {
                $email = $recip['email'];
                $username = $recip['username'];
                $emails[] = Yii::$app
                    ->mailer
                    ->compose(
                        ['html' => 'ticketStatus-html', 'text' => 'ticketStatus-text'],
                        ['ticket' => $ticketModel,
                        'username' => $username,
                        'jobLabels' => $jobLabels,
                        'primary_tech' => $primaryTechName]
                    )
                    ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
                    ->setTo($email)
                    ->setSubject('Ticket #' . $ticketModel->id . ' ' . strtolower(JobStatus::findOne($ticketModel->job_status_id)->name) . '. ' . Yii::$app->name);
            }
        }
        // send to the requester's email
        array_push($emails, 
            Yii::$app
                ->mailer
                ->compose(
                    ['html' => 'ticketStatus-html', 'text' => 'ticketStatus-text'],
                    ['ticket' => $ticketModel,
                    'username' => $ticketModel->requester,
                    'jobLabels' => $jobLabels,
                    'primary_tech' => $primaryTechName]
                )
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
                ->setTo($ticketModel->requester_email)
                ->setSubject('CABOCES Ticket #' . $ticketModel->id . ' ' . strtolower(JobStatus::findOne($ticketModel->job_status_id)->name) . '. ' . Yii::$app->name)
        );
        // send to primary tech (they are technically applied to this ticket in tech_ticket_assignment i dont think)
        array_push($emails, 
            Yii::$app
                ->mailer
                ->compose(
                    ['html' => 'ticketStatus-html', 'text' => 'ticketStatus-text'],
                    ['ticket' => $ticketModel,
                    'username' => $primaryTechName,
                    'jobLabels' => $jobLabels,
                    'primary_tech' => $primaryTechName]
                )
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
                ->setTo($ticketModel->requester_email)
                ->setSubject('Ticket #' . $ticketModel->id . ' ' . strtolower(JobStatus::findOne($ticketModel->job_status_id)->name) . '. ' . Yii::$app->name)
        );
        if (!empty($emails)) {
            // send multiple to save bandwidth
            return Yii::$app->mailer->sendMultiple($emails);
        } else {
            // no emails to deliver
            return false;
        }
    }

    /**
     * Get the assets columns and data provider for the /create and /update ticket pages
     */
    private function getTicketAssetGridProperties($model) {
        // get asset provider and columns
        $assetProvider = new ActiveDataProvider([
            'query' => Asset::find()->where(['ticket_id' => $model->id]),
            'sort' => [
                'defaultOrder' => [
                    'created' => SORT_ASC
                ]
            ]
        ]);
        // asset columns
        $assetColumns = [
            [
                'class' => ActionColumn::class,
                'template' => '{delete}',
                'urlCreator' => function ($action, Asset $model, $key, $index) {
                    return Url::to(['asset/' . $action, 'id' => $model->id]);
                }
            ],
            'new_prop_tag' => [
                'attribute' => 'new_prop_tag',
                'label' => 'Asset Tag',
            ],
            [
                'value' => function(Asset $model) {
                    $item = $model->inventory;
                    return '<a href="/inventory/view?new_prop_tag=' . ($model->new_prop_tag != null ? $model->new_prop_tag : '') . '">' . ($item->item_description != null ? $item->item_description : '') . '</a>';
                },
                'label' => 'Asset Description',
                'format' => 'raw',
            ],
            [
                'attribute' => 'last_modified_by',
                'value' => function(Asset $model) {
                    return '<a href="/user/view?id=' . ($model->last_modified_by_user_id != null ? $model->last_modified_by_user_id : '') . '">' . ($model->last_modified_by_user_id != null ? $model->lastModifiedByUser->username : '') . '</a>';
                },
                'format' => 'raw',
            ]
        ];
        return ['provider' => $assetProvider, 'columns' => $assetColumns];
    }

    /**
     * Get the parts columns and data provider for the /create and /update ticket pages
     */
    private function getTicketPartsGridProperties($model) {
        // get parts provider and columns
        $partsProvider = new ActiveDataProvider([
            'query' => Part::find()->where(['ticket_id' => $model->id]),
            'sort' => [
                'defaultOrder' => [
                    'created' => SORT_ASC
                ]
            ]
        ]);
        // parts columns
        $partsColumns = [
            [
                'class' => ActionColumn::class,
                'template' => '{delete}',
                'urlCreator' => function ($action, Part $model, $key, $index) {
                    return Url::to(['part/' . $action, 'id' => $model->id]);
                }
            ],
            'part_number' => [
                'attribute' => 'part_number',
            ],
            'part_name' => [
                'attribute' => 'part_name'
            ],
            'quantity' => [
                'attribute' => 'quantity'
            ],
            'unit_price' => [
                'attribute' => 'unit_price',
            ],
            'pending_delivery' => [
                'attribute' => 'pending_delivery',
                'value' => function(Part $model) {
                    return $model->pending_delivery == 1? 'Yes' : 'No';
                },
            ],
            'note' => [
                'attribute' => 'note',
            ],
            [
                'attribute' => 'last_modified_by',
                'value' => function(Part $model) {
                    return '<a href="/user/view?id=' . ($model->last_modified_by_user_id != null ? $model->last_modified_by_user_id : '') . '">' . ($model->last_modified_by_user_id != null ? $model->lastModifiedByUser->username : '') . '</a>';
                },
                'format' => 'raw',
            ],
        ];
        return ['provider' => $partsProvider, 'columns' => $partsColumns];
    }

    /**
     * Get the ticket note columns and data provider for the /create and /update ticket pages
     */
    private function getTicketNotesGridProperties($model) {
        // get ticket note provider and columns
        $ticketNotesProvider = new ActiveDataProvider([
            'query' => TicketNote::find()->where(['ticket_id' => $model->id]),
            'sort' => [
                'defaultOrder' => [
                    'created' => SORT_ASC
                ]
            ]
        ]);
        // ticket note columns
        $ticketNotesColumns = [
            [
                'class' => ActionColumn::class,
                'template' => '{delete}',
                'urlCreator' => function ($action, TicketNote $model, $key, $index) {
                    return Url::to(['ticket-note/' . $action, 'id' => $model->id]);
                }
            ],
            'note' => [
                'attribute' => 'note',
            ],
            [
                'attribute' => 'last_modified_by',
                'value' => function(TicketNote $model) {
                    return '<a href="/user/view?id=' . ($model->last_modified_by_user_id != null ? $model->last_modified_by_user_id : '') . '">' . ($model->last_modified_by_user_id != null ? $model->lastModifiedByUser->username : '') . '</a>';
                },
                'format' => 'raw',
            ],
            'created' => [
                'attribute' => 'created',
                'value' => function (TicketNote $model) {
                    return Yii::$app->formatter->asDate($model->created, 'php:F jS, Y');
                }
            ]
        ];
        return ['provider' => $ticketNotesProvider, 'columns' => $ticketNotesColumns];
    }
}