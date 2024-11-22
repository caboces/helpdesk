<?php

namespace app\controllers;

use Yii;

use app\models\Inventory;
use app\models\InventorySearch;
use app\models\LoanedInventory;
use app\models\LoanedInventorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

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
        $loanedInvSearchModel = new LoanedInventorySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $loanedInvDataProvider = $loanedInvSearchModel->search($this->request->queryParams);

        $this->layout = 'blank-container';
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'loanedInvSearchModel' => $loanedInvSearchModel,
            'loanedInvDataProvider' => $loanedInvDataProvider
        ]);
    }

    /**
     * Displays the create loaned inventory page
     */
    public function actionCreateLoanedInventory() {
        $model = new LoanedInventory();
        if ($this->request->isPost) {
            $model->load($this->request->post());
            if (!$model->validate()) {
                $errors = $model->errors;
                Yii::$app->response->statusCode = 400; // bad request

                // we had errors, go back
                return $this->render('createLoanedInventory', [
                    'model' => $model,
                    'errors' => $errors,
                    // map to an array of just ids
                    'newPropTags' => ArrayHelper::map(Inventory::find()->select('new_prop_tag')->all(), 'new_prop_tag', 'new_prop_tag')
                ]);
            }
            $model->save();
            return $this->render('view', [
                'model' => $this->findModel($model->new_prop_tag)
            ]);
        }
        return $this->render('createLoanedInventory', [
            'model' => $model,
            // map to an array of just ids
            'newPropTags' => ArrayHelper::map(Inventory::find()->select('new_prop_tag')->all(), 'new_prop_tag', 'new_prop_tag')
        ]);
    }

        /**
     * Displays the create loaned inventory page
     */
    public function actionReturnLoanedInventory() {
        $model = new LoanedInventory();
        if ($this->request->isPost) {
            $model = LoanedInventory::find()->where([
                'loaned_inventory.new_prop_tag' => $this->request->post('LoanedInventory')['new_prop_tag']
            ])->one();
            if (!$model->validate()) {
                $errors = $model->errors;
                Yii::$app->response->statusCode = 400; // bad request

                // we had errors, go back
                return $this->render('returnLoanedInventory', [
                    'model' => $model,
                    'errors' => $errors,
                    // map to an array of just ids that were loaned
                    'newPropTags' => ArrayHelper::map(LoanedInventory::find()->select('new_prop_tag')->all(), 'new_prop_tag', 'new_prop_tag')
                ]);
            }
            $model->delete();
            return $this->render('view', [
                'model' => $this->findModel($model->new_prop_tag)
            ]);
        }
        return $this->render('returnLoanedInventory', [
            'model' => $model,
            // map to an array of just ids that were loaned
            'newPropTags' => ArrayHelper::map(LoanedInventory::find()->select('new_prop_tag')->all(), 'new_prop_tag', 'new_prop_tag')
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
