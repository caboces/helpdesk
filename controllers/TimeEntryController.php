<?php

namespace app\controllers;

use app\models\Ticket;
use yii\web\Controller;
use app\models\TimeEntry;
use yii\filters\VerbFilter;
use app\models\TimeEntrySearch;
use yii\web\NotFoundHttpException;

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
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param int $id of the ticket model time is being added to
     * @return string|\yii\web\Response
     */
    public function actionCreate($id)
    {
        $ticket = Ticket::findOne($id);
        $model = new TimeEntry();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['time-entry/view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        $this->layout = 'blank';

        return $this->renderAjax('create', [
            'model' => $model,
            'ticket' => $ticket
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
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
     * Creates a new TimeEntry model within a modal from the Ticket form.
     * 
     * This can be seen in the Ticket Form
     * Creation views are shown in a modal window, hence the modal-blank layout
     * 
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param int $id of the ticket model time is being added to
     * @return string|\yii\web\Response
     */
    public function actionCreateModal($id)
    {
        $ticket = Ticket::findOne($id);
        $model = new TimeEntry();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ['code' => 200, 'message' => 'success'];
            }
        } else {
            $model->loadDefaultValues();
        }

        $this->layout = 'blank';

        return $this->renderAjax('create', [
            'model' => $model,
            'ticket' => $ticket
        ]);
    }
}
