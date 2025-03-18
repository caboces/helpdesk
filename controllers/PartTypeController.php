<?php

namespace app\controllers;

use app\models\PartType;
use app\models\PartTypeSearch;
use Yii;
use yii\bootstrap5\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;

/**
 * PartTypeController implements the CRUD actions for PartType model.
 */
class PartTypeController extends Controller
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
     * Lists all PartType models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PartTypeSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $inactivePartTypesSearchModel = new PartTypeSearch();
        $inactivePartTypesSearchModel->search_inactive_part_types = true;
        $inactivePartTypesDataProvider = $searchModel->search($this->request->queryParams);

        $this->layout = 'blank-container';
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'inactivePartTypesDataProvider' => $inactivePartTypesDataProvider,
            'inactivePartTypesSearchModel' => $inactivePartTypesSearchModel,
        ]);
    }

    /**
     * Displays a single PartType model.
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
     * Creates a new PartType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new PartType();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->validate()) {

                // check if the part already exists
                if (PartType::find()->where(['name' => $model->name])->exists()) {
                    Yii::$app->session->setFlash('error', "The provided Part Type with name '$model->name' already exists.");
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }

                // capitalize the name so we are consistent
                ucwords($model->name);

                $model->save();
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                // failed
                Yii::$app->session->setFlash('error', Html::errorSummary($model));

                $this->layout = 'blank-container';
                return $this->render('create', [
                    'model' => $model
                ]);
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
     * Updates an existing PartType model.
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
     * Deletes an existing PartType model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        $this->layout = 'blank-container';
        return $this->redirect(['index']);
    }

    /**
     * Finds the PartType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return PartType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PartType::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
