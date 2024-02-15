<?php

namespace app\controllers;

use Yii;
use app\models\Ticket;
use app\models\JobType;
use yii\web\Controller;
use app\models\JobStatus;
use app\models\JobCategory;
use app\models\JobPriority;
use yii\filters\VerbFilter;
use app\models\TicketSearch;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use app\models\TicketAssignmentSearch;
use app\models\TicketResolvedClosedSearch;
use app\models\User;

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
        // search all tickets
        $ticketSearch = new TicketSearch();
        $dataProvider = $ticketSearch->search(Yii::$app->request->get());
        // search ticket tags
        $categories = ArrayHelper::map(JobCategory::getCategories(), 'name', 'name');
        $priorities = ArrayHelper::map(JobPriority::getPriorities(), 'name', 'name');
        $statuses = ArrayHelper::map(JobStatus::getStatuses(), 'name', 'name');
        $types = ArrayHelper::map(JobType::getTypes(), 'name', 'name');

        $this->layout = 'blank';
        return $this->render('index', [
            // search all tickets
            'searchModel' => $ticketSearch,
            'dataProvider' => $dataProvider,
            // search ticket tags
            'categories' => $categories,
            'priorities' => $priorities,
            'statuses' => $statuses,
            'types' => $types
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

        $categories = ArrayHelper::map(JobCategory::getCategories(), 'id', 'name');
        $priorities = ArrayHelper::map(JobPriority::getPriorities(), 'id', 'name');
        $statuses = ArrayHelper::map(JobStatus::getStatuses(), 'id', 'name');
        $types = ArrayHelper::map(JobType::getTypes(), 'id', 'name');

        $users = ArrayHelper::map(User::getUsers(), 'id', 'username');

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {

                if(!empty($_POST['Ticket']['users'])) {
                    foreach($_POST['Ticket']['users'] as $user_id) {
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
            'categories' => $categories,
            'priorities' => $priorities,
            'statuses' => $statuses,
            'types' => $types,
            'users' => $users
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
        $model = $this->findModel($id);

        // used for populating select elements with user friendly words instead of senseless numbers
        $categories = ArrayHelper::map(JobCategory::getCategories(), 'id', 'name');
        $priorities = ArrayHelper::map(JobPriority::getPriorities(), 'id', 'name');
        $statuses = ArrayHelper::map(JobStatus::getStatuses(), 'id', 'name');
        $types = ArrayHelper::map(JobType::getTypes(), 'id', 'name');

        $users = ArrayHelper::map(User::getUsers(), 'id', 'username');

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {

            // To update the tech_ticket_assignment junction table
            // This is kind of a lazy way of doing this, probably should check to see if any records need to be deleted instead of always doing it, but I'm keeping it for now :)
            // First delete existing tech assignments in the table (otherwise it will not touch removed techs if you remove some but not all from a list)
            foreach($model->users as $user) {
                // pass "true" because we want to delete the records, not just nullify all columns
                $model->unlink('users', $user, true);
            }
            // If the selection is empty, we are done. If there is some data, insert a row into the tech_ticket_assignment table for each selected tech
            if(!empty($_POST['Ticket']['users'])) {
                foreach($_POST['Ticket']['users'] as $user_id) {
                    $user = User::findOne($user_id);
                    $model->link('users', $user);
                }
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'categories' => $categories,
            'priorities' => $priorities,
            'statuses' => $statuses,
            'types' => $types,
            'users' => $users
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
}
