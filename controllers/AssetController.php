<?php

namespace app\controllers;

use app\models\Asset;
use app\models\Ticket;
use yii\web\Controller;
use app\models\AssetSearch;
use Yii;
use yii\base\Model;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;

/**
 * AssetController implements the CRUD actions for Asset model.
 */
class AssetController extends Controller
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
     * Lists all Asset models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new AssetSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Asset model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $this->layout = 'blank';
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Asset model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $models = [new Asset()];
        $ticket_id = Yii::$app->request->get('ticket_id');

        if ($this->request->isPost) {
            // figure out how many Asset models we need to upload
            $count = count($this->request->post('Asset'));
            for ($i = 0; $i < $count; $i++) {
                $models[$i] = new Asset();
            }
            // validate and load
            if (Asset::loadMultiple($models, $this->request->post()) && Asset::validateMultiple($models)) {
                foreach ($models as $model) {
                    // do not run validation since we already did, save each one
                    $model->save(false);
                }
            } else {
                // form errors
                Yii::$app->session->setFlash('error', Html::errorSummary($models));
                return $this->redirect(Yii::$app->request->referrer);
            }
            return $this->redirect("/ticket/update?id=$ticket_id&tabPane=pills-assets-tab");
        } else {
            foreach ($models as $model) {
                $model->loadDefaultValues();
            }
        }

        $this->layout = 'blank';
        return $this->renderAjax('create', [
            'models' => $models,
            'ticket_id' => $ticket_id,
        ]);
    }

    /**
     * Updates an existing Asset model.
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

        $this->layout = 'blank';
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Asset model.
     * If deletion is successful, the browser will be redirected to the 'ticket/update' page.
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
     * Finds the Asset model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Asset the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Asset::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
