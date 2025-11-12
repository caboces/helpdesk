<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\JobCategory;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;

/**
 * JobCategoryController implements the CRUD actions for JobCategory model.
 */
class JobCategoryController extends Controller
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
                        'roles' => ['@'],
                        'allow' => true,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all JobCategory models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => JobCategory::find(),
            'pagination' => [
                'pageSize' => 10
            ],
            'sort' => [
                'defaultOrder' => [
                    'name' => SORT_ASC,
                ]
            ],
        ]);

        $dataProviderInactive = new ActiveDataProvider([
            // TODO: Assuming anything other than '10' is inactive
            'query' => JobCategory::find()->where("status <> 10"),
            'pagination' => [
                'pageSize' => 10
            ],
            'sort' => [
                'defaultOrder' => [
                    'name' => SORT_ASC,
                ]
            ],
        ]);

        $this->layout = 'blank-container';
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'dataProviderInactive' => $dataProviderInactive,
        ]);
    }

    /**
     * Displays a single JobCategory model.
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
     * Creates a new JobCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new JobCategory();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        $this->layout = 'blank-container';
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing JobCategory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $this->layout = 'blank-container';
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing JobCategory model.
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
     * Toggles status
     * If toggle is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return \yii\web\Response
     * @throws ForbiddenHttpException if the user does not have permissions
     */
    public function actionToggleStatus($id)
    {
        if (Yii::$app->user->can('change-user-status')) {
            $model = $this->findModel($id);

            if ($model->status === 10) {
                $model->status = 9; // if active, deactivate
            } else {
                $model->status = 10; // if inactive, activate
            }

            if ($this->request->isPost && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                throw new ForbiddenHttpException('You do not have permission to change user activation.');
            }
        }
    }

    /**
     * Finds the JobCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return JobCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = JobCategory::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
