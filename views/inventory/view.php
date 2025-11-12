<?php

use app\models\Inventory;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Inventory $model */

$this->title = 'View Inventory: ' . $model->new_prop_tag;
$this->params['breadcrumbs'][] = ['label' => 'Inventories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="inventory-view">

<div class="title-icon d-flex align-items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="2rem" height="2rem" fill="currentColor" class="bi bi-binoculars-fill" viewBox="0 0 16 16">
            <path d="M4.5 1A1.5 1.5 0 0 0 3 2.5V3h4v-.5A1.5 1.5 0 0 0 5.5 1zM7 4v1h2V4h4v.882a.5.5 0 0 0 .276.447l.895.447A1.5 1.5 0 0 1 15 7.118V13H9v-1.5a.5.5 0 0 1 .146-.354l.854-.853V9.5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v.793l.854.853A.5.5 0 0 1 7 11.5V13H1V7.118a1.5 1.5 0 0 1 .83-1.342l.894-.447A.5.5 0 0 0 3 4.882V4zM1 14v.5A1.5 1.5 0 0 0 2.5 16h3A1.5 1.5 0 0 0 7 14.5V14zm8 0v.5a1.5 1.5 0 0 0 1.5 1.5h3a1.5 1.5 0 0 0 1.5-1.5V14zm4-11H9v-.5A1.5 1.5 0 0 1 10.5 1h1A1.5 1.5 0 0 1 13 2.5z" />
        </svg>
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'item_description',
            'serial_number',
            'new_prop_tag' => [
                'attribute' => 'new_prop_tag',
                'label' => 'Asset Tag',
            ],
            'fund_id' => [
                'label' => 'Funds',
                'value' => function (Inventory $model) {
                    return Html::tag('span', Html::tag('b', "$model->fund_id:&nbsp;") . $model->fund->fund_description);
                },
                'format' => 'raw'
            ],
            'bl_code' => [
                'label' => 'Location',
                'value' => function (Inventory $model) {
                    return $model->blCode->bl_name;
                }
            ],
            'vendor_id' => [
                'label' => 'Vendor',
                'value' => function (Inventory $model) {
                    return $model->vendor->vendor_name;
                }
            ],
            'tagged' => [
                'label' => 'Tagged',
                'value' => function (Inventory $model) {
                    return $model->tagged === -1 ? 'No' : 'Yes';
                }
            ],
            'qty',
            'purchased_date' => [
                'label' => 'Purchased Date',
                'value' => function (Inventory $model) {
                    return Yii::$app->dateUtils->asDate($model->purchased_date);
                }
            ],
            'date_purchased_num',
            'po' => [
                'label' => 'PO Number',
                'attribute' => 'po',
            ],
            'unit_price' => [
                'label' => 'Unit Price',
                'value' => function (Inventory $model) {
                    return Yii::$app->formatter->asCurrency($model->unit_price);
                }
            ],
            'total_price' => [
                'label' => 'Total Price',
                'value' => function (Inventory $model) {
                    return Yii::$app->formatter->asCurrency($model->total_price);
                }
            ],
            'useful_life',
            'old_prop_tag',
            'entry_date' => [
                'label' => 'Entry Date',
                'value' => function (Inventory $model) {
                    return Yii::$app->dateUtils->asDate($model->entry_date);
                }
            ],
            'last_modified_date' => [
                'label' => 'Last Modified Date',
                'value' => function (Inventory $model) {
                    return Yii::$app->dateUtils->asDate($model->last_modified_date);
                }
            ],
        ],
    ]) ?>

</div>