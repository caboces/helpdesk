<?php

use app\models\District;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\DistrictSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'District Management';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="district-index">

    <div class="title-icon d-flex align-items-center">
        <i class="fa-solid fa-school fa-2xl"></i>
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    
    <p>This is the District Management page. Districts are specific schools that are associated with and use CABOCES services.</p>
    
    <!-- action buttons -->
    <div class='container-fluid p-2 | bg-dark shadow-sm'>
        <?= Html::a('Reset filters', ['index'], ['class' => 'btn btn-secondary']); ?>
        <?= Html::a(Html::decode('<i class="fa-solid fa-plus"></i>') . ' Create', ['create'], ['class' => 'btn btn-primary']) ?>
    </div>


    <!-- pill nav -->
    <ul class="nav nav-pills" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pills-active-districts-tab" data-bs-toggle="pill" data-bs-target="#pills-active-districts" type="button" role="tab" aria-controls="pills-active-districts" aria-selected="true">Active Districts</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-inactive-districts-tab" data-bs-toggle="pill" data-bs-target="#pills-inactive-districts" type="button" role="tab" aria-controls="pills-inactive-districts" aria-selected="false">Inactive Districts</button>
        </li>
    </ul>

    <!-- pill content -->
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-active-districts" role="tabpanel" aria-labelledby="pills-active-districts-tab">
            <div class="subsection-info-block">
                <h2>Active Districts</h2>
                <p>These districts can be used when creating or updating tickets. They cannot be deleted.</p>
                <?php Pjax::begin(['id' => 'grid-active-districts']) ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        [
                            'header' => 'Actions',
                            'class' => ActionColumn::class,
                            'template' => '{view} {update}',
                            'urlCreator' => function ($action, District $model, $key, $index, $column) {
                                return Url::toRoute([$action, 'id' => $model->id]);
                            }
                        ],
                        'id',
                        'name',
                        'description',
                        'component_district' => [
                            'attribute' => 'component_district',
                            'label' => 'Component District?',
                            'value' => function (District $model) {
                                return $model->component_district === 1 ? 
                                    Html::tag('span', 'Yes ' . Html::decode('<i class="fa-solid fa-check"></i>'), ['class' => 'text-success'])
                                    : Html::tag('span', 'No ' . Html::decode('<i class="fa-solid fa-xmark"></i>'), ['class' => 'text-danger']);
                            },
                            'format' => 'raw',
                        ],
                        'created' => [
                            'attribute' => 'created',
                            'label' => 'Date Created',
                            'value' => function (District $model) {
                                return Yii::$app->dateUtils->asDate($model->created);
                            },
                        ],
                        'modified' => [
                            'attribute' => 'modified',
                            'label' => 'Date Modified',
                            'value' => function (District $model) {
                                return Yii::$app->dateUtils->asDate($model->modified);
                            },
                        ]
                    ],
                ]); ?>
                <?php Pjax::end() ?>
            </div>
        </div>
        <div class="tab-pane fade show active" id="pills-inactive-districts" role="tabpanel" aria-labelledby="pills-inactive-districts-tab">
            <div class="subsection-info-block">
                <h2>Inactive Districts</h2>
                <p>These districts cannot be used in creating or updating tickets. They also cannot be deleted for billing purposes.</p>
                <?php Pjax::begin(['id' => 'grid-inactive-districts']) ?>
                <?= GridView::widget([
                    'dataProvider' => $inactiveDistrictsDataProvider,
                    'filterModel' => $inactiveDistrictsSearchModel,
                    'columns' => [
                        [
                            'header' => 'Actions',
                            'class' => ActionColumn::class,
                            'template' => '{view} {update}',
                            'urlCreator' => function ($action, District $model, $key, $index, $column) {
                                return Url::toRoute([$action, 'id' => $model->id]);
                            }
                        ],
                        'id',
                        'name',
                        'description',
                        'component_district' => [
                            'attribute' => 'component_district',
                            'label' => 'Component District?',
                            'value' => function (District $model) {
                                return $model->component_district === 1 ? 
                                    Html::tag('span', 'Yes ' . Html::decode('<i class="fa-solid fa-check"></i>'), ['class' => 'text-success'])
                                    : Html::tag('span', 'No ' . Html::decode('<i class="fa-solid fa-xmark"></i>'), ['class' => 'text-danger']);
                            },
                            'format' => 'raw',
                        ],
                        'created' => [
                            'attribute' => 'created',
                            'label' => 'Date Created',
                            'value' => function (District $model) {
                                return Yii::$app->dateUtils->asDate($model->created);
                            },
                        ],
                        'modified' => [
                            'attribute' => 'modified',
                            'label' => 'Date Modified',
                            'value' => function (District $model) {
                                return Yii::$app->dateUtils->asDate($model->modified);
                            },
                        ]
                    ],
                ]); ?>
                <?php Pjax::end() ?>
            </div>
        </div>
    </div>


</div>
