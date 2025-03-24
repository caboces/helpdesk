<?php

use app\models\Asset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\AssetSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Asset Management';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="asset-index">

    <div class="title-icon d-flex align-items-center">
        <i class="fa-solid fa-barcode fa-2xl"></i>
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <p>This is the Asset Managment page. You can make edits to assets, remove assets, or view assets.</p>
    <p>You can only create assets while viewing a ticket.</p>
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'header' => 'Actions',
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, Asset $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
            'ticket_id' => [
                'attribute' => 'ticket_id',
                'label' => 'Ticket',
                'value' => function (Asset $model) { 
                    return Html::a($model->ticket->summary . Html::decode(' <i class="fa-solid fa-arrow-up-right-from-square"></i>'), Url::toRoute(['/ticket/view', 'id' => $model->ticket_id]), ['target' => '_blank', 'data-pjax' => 0]);
                },
                'format' => 'raw',
            ],
            'new_prop_tag' => [
                'attribute' => 'new_prop_tag',
                'value' => function (Asset $model) {
                    return Html::a($model->inventory->item_description . Html::decode(' <i class="fa-solid fa-arrow-up-right-from-square"></i>'), Url::toRoute(['/inventory/view', 'new_prop_tag' => $model->new_prop_tag]), ['target' => '_blank', 'data-pjax' => 0]);
                },
                'format' => 'raw',
            ],
            'po_number' => [
                'attribute' => 'po_number',
                'value' => function (Asset $model) {
                    if ($model->po_number) {
                        return Html::a($model->po_number . Html::decode(' <i class="fa-solid fa-arrow-up-right-from-square"></i>'), Url::toRoute(['/inventory/index', 'InventorySearch[po]' => $model->po_number]), ['target' => '_blank', 'data-pjax' => 0]);
                    } else {
                        return '';
                    }
                },
                'format' => 'raw',
            ],
            'created' => [
                'attribute' => 'created',
                'label' => 'Date Created',
                'value' => function (Asset $model) {
                    return Yii::$app->dateUtils->asDate($model->created);
                }
            ],
            'modified' => [
                'attribute' => 'modified',
                'label' => 'Date Modified',
                'value' => function (Asset $model) {
                    return Yii::$app->dateUtils->asDate($model->modified);
                }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
