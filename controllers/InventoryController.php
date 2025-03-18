<?php

namespace app\controllers;

use app\models\DeleteReason;
use app\models\Fund;
use Yii;

use app\models\Inventory;
use app\models\InventoryClass;
use app\models\InventorySearch;
use app\models\LoanedInventory;
use app\models\LoanedInventorySearch;
use app\models\Location;
use app\models\Vendor;
use yii\bootstrap5\Html;
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
                    'class' => VerbFilter::class,
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
     * More refined search details for inventory
     */
    public function actionSearch() {
        $fundsOptions = ArrayHelper::map(Fund::find()->select(['fund_id', 'fund_description'])->orderBy('fund_description ASC')->all(), 'fund_id', 'fund_description');
        $locationsOptions = ArrayHelper::map(Location::find()->select(['bl_code', 'bl_name'])->orderBy('bl_name ASC')->all(), 'bl_code', 'bl_name');
        $deleteOptions = ArrayHelper::map(DeleteReason::find()->select(['delete_code', 'reason_deleted'])->orderBy('reason_deleted ASC')->all(), 'delete_code', 'reason_deleted');
        $classOptions = ArrayHelper::map(InventoryClass::find()->select(['class_id', 'class_description'])->orderBy('class_description ASC')->all(), 'class_id', 'class_description');
        $vendorsOptions = ArrayHelper::map(Vendor::find()->select(['vendor_id', 'vendor_name'])->orderBy('vendor_name ASC')->all(), 'vendor_id', 'vendor_name');

        $this->layout = 'blank-container';
        return $this->render('search', [
            'model' => new InventorySearch(),
            'fundsOptions' => $fundsOptions,
            'locationsOptions' => $locationsOptions,
            'deleteOptions' => $deleteOptions,
            'classOptions' => $classOptions,
            'vendorsOptions' => $vendorsOptions,
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
