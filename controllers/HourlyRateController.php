<?php

namespace app\controllers;

use app\models\HourlyRate;
use app\models\JobType;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * HourlyRateController implements the CRUD actions for HourlyRate model.
 */
class HourlyRateController extends Controller
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
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all HourlyRate models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => HourlyRate::find()->where('CURRENT_TIMESTAMP BETWEEN hourly_rate.first_day_effective AND hourly_rate.last_day_effective'),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
            */
        ]);
        $inactiveHourlyRatesDataProvider = new ActiveDataProvider([
            'query' => HourlyRate::find()->where('CURRENT_TIMESTAMP NOT BETWEEN hourly_rate.first_day_effective AND hourly_rate.last_day_effective')
        ]);

        $this->layout = 'blank-container';
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'inactiveHourlyRatesDataProvider' => $inactiveHourlyRatesDataProvider,
        ]);
    }

    /**
     * Displays a single HourlyRate model.
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
     * Creates a new HourlyRate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new HourlyRate();
        $jobTypesOptions = ArrayHelper::map(JobType::find()->select(['id', 'name'])->where(['status' => 10])->orderBy('name ASC')->all(), 'id', 'name');

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
            'jobTypesOptions' => $jobTypesOptions,
        ]);
    }

    /**
     * Updates an existing HourlyRate model.
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
     * Deletes an existing HourlyRate model.
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
     * Finds the HourlyRate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return HourlyRate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = HourlyRate::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
