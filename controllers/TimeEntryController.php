<?php

namespace app\controllers;

use app\models\TechTicketAssignment;
use app\models\Ticket;
use yii\web\Controller;
use app\models\TimeEntry;
use yii\filters\VerbFilter;
use app\models\TimeEntrySearch;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * TimeEntryController implements the CRUD actions for TimeEntry model.
 */
class TimeEntryController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all TimeEntry models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new TimeEntrySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $this->layout = 'blank-container';
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TimeEntry model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $this->layout = 'blank-container';
        
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TimeEntry model.
     * 
     * If creation is successful, the browser will be redirected whatever 'redirect' was defined to.
     * @param int $id of the ticket model time is being added to
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $models = [new TimeEntry()];
        $ticket_id = $this->request->get('ticket_id');
        $view_or_update_redirect = $this->request->get('redirect')?? "/ticket/view";

        if ($this->request->isPost) {
            $count = count($this->request->post('TimeEntry'));
            for ($i = 0; $i < $count; $i++) {
                $models[$i] = new TimeEntry();
            }
            // validate and load
            if (TimeEntry::loadMultiple($models, $this->request->post()) && TimeEntry::validateMultiple($models)) {
                foreach ($models as $model) {
                    // do not run validation since we already did
                    $model->save(false);
                }
                // redirect to ticket update OR view. ticket_id should be valid here if it passed model validation
                return $this->redirect([$view_or_update_redirect, 'id' => $ticket_id]);
            } else {
                // form errors
                $errors = [];
                foreach ($models as $model) {
                    if ($model->hasErrors()) {
                        $errors[$model->user->username] = $model->getErrors();
                    }
                }
                // timeEntryErrors looks like this:
                // array:4 [▼
                //  "mary_poppins" => array:1 [▼
                //      "entry_date" => array:1 [▼
                //          0 => "Please select the date of the hours worked."
                //      ]
                //   ]
                // "admin" => array:2 [▼
                //      "entry_date" => array:1 [▼
                //          0 => "Please select the date of the hours worked."
                //      ]
                // "user_id" => array:1 [▼
                //     0 => "User ID is required. Please make sure the relevant tech is assigned to the ticket."
                //      ]
                //    ]
                // ]
                Yii::$app->session->setFlash('timeEntryErrors', $errors);
                return $this->redirect([$view_or_update_redirect, 'id' => $ticket_id]);
            }
        } else {
            foreach ($models as $model) {
                $model->loadDefaultValues();
            }
        }

        $this->layout = 'blank';

        return $this->renderAjax('create', [
            'models' => $models,
            'ticket_id' => $ticket_id
        ]);
    }

    /**
     * Updates an existing TimeEntry model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $ticket = Ticket::findOne($model->ticket_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $this->layout = 'blank-container';

        return $this->render('update', [
            'model' => $model,
            'ticket' => $ticket
        ]);
    }

    /**
     * Deletes an existing TimeEntry model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $ticket_id = $this->findModel($id)->ticket_id;
        $this->findModel($id)->delete();

        return $this->redirect(['/ticket/update', 'id' => $ticket_id]);
    }

    /**
     * Finds the TimeEntry model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return TimeEntry the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TimeEntry::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Finds the time entries for the specified tech.
     */
    public function actionCheckEntries() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $tech_id = Yii::$app->request->get('tech_id');
        $ticket_id = Yii::$app->request->get('ticket_id');
        $entriesExist = TimeEntry::find()->where("user_id = {$tech_id} AND ticket_id = {$ticket_id}")->exists();

        return ['exists' => $entriesExist];
    }
}
