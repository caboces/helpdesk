<?php

namespace app\controllers;

use app\models\Inventory;
use app\models\InventorySearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * InventoryController implements the CRUD actions for Inventory model.
 */
class InventoryController extends Controller
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
     * Lists all Inventory models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new InventorySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $this->layout = 'blank-container';
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Inventory model.
     * @param int $new_prop_tag New Prop Tag
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($new_prop_tag)
    {
        return $this->render('view', [
            'model' => $this->findModel($new_prop_tag),
        ]);
    }

    /**
     * Finds the Inventory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $new_prop_tag New Prop Tag
     * @return Inventory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($new_prop_tag)
    {
        if (($model = Inventory::findOne(['new_prop_tag' => $new_prop_tag])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
