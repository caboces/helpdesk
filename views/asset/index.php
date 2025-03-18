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

$this->title = 'Asset';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="asset-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Asset', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

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
            'id',
            'ticket_id',
            'new_prop_tag' => [
                'attribute' => 'new_prop_tag',
                'value' => function (Asset $model) {
                    return Html::a($model->new_prop_tag, Url::to("/inventory/view?new_prop_tag=$model->new_prop_tag", ['target' => '_blank']));
                },
                'format' => 'raw',
            ],
            'po_number' => [
                'attribute' => 'po_number',
                'value' => function (Asset $model) {
                    return Html::a($model->po_number, Url::to("/inventory/index?InventorySearch[po]=$model->po_number", ['target' => '_blank']));
                },
                'format' => 'raw',
            ],
            'created',
            'modified',
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
