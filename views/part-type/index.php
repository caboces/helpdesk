<?php

use app\models\PartType;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\PartTypeSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Part Type Management';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="part-type-index">

    <div class="title-icon d-flex align-items-center">
        <i class="fa-solid fa-list fa-2xl"></i>
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <p>This is the Part Types Management page.</p>
    <p>Part Types reference the generic type of a part for categorical and reference purposes.</p>
    <!-- action buttons -->
    <div class='container-fluid p-2 | bg-dark shadow-sm'>
        <?= Html::a('Reset filters', ['index'], ['class' => 'btn btn-secondary']); ?>
        <?= Html::a(Html::decode('<i class="fa-solid fa-plus"></i>') . ' Create', ['/part-type/create'], ['class' => 'btn btn-primary']) ?>
    </div>

    <!-- pill nav -->
    <ul class="nav nav-pills" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pills-active-part-types-tab" data-bs-toggle="pill" data-bs-target="#pills-active-part-types" type="button" role="tab" aria-controls="pills-active-part-types" aria-selected="true">Active Part Types</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-inactive-part-types-tab" data-bs-toggle="pill" data-bs-target="#pills-inactive-part-types" type="button" role="tab" aria-controls="pills-inactive-part-types" aria-selected="false">Inactive Part Types</button>
        </li>
    </ul>

    <!-- pill content -->
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-active-part-types" role="tabpanel" aria-labelledby="pills-active-part-types-tab">
            <div class="subsection-info-block">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'header' => 'Actions',
                        'class' => ActionColumn::class,
                        'urlCreator' => function ($action, PartType $model, $key, $index, $column) {
                            return Url::toRoute([$action, 'id' => $model->id]);
                        }
                    ],
                    'id',
                    'name',
                    'description',
                    'created_by' => [
                        'attribute' => 'created_by',
                        'value' => function (PartType $model) {
                            return Html::a($model->createdBy->username . Html::decode(' <i class="fa-solid fa-arrow-up-right-from-square"></i>'),
                                Url::toRoute(['/user/view', 'id' => $model->created_by]), ['target' => '_blank',]);
                        },
                        'format' => 'raw',
                        'enableSorting' => false,
                        'filter' => false,
                    ],
                    'created' => [
                        'attribute' => 'created',
                        'label' => 'Date Created',
                        'value' => function (PartType $model) {
                            return Yii::$app->dateUtils->asDate($model->created);
                        }
                    ],
                    'modified' => [
                        'attribute' => 'modified',
                        'label' => 'Date Modified',
                        'value' => function (PartType $model) {
                            return Yii::$app->dateUtils->asDate($model->modified);
                        }
                    ],
                ],
            ]); ?>
            </div>
        </div>
        <div class="tab-pane fade" id="pills-inactive-part-types" role="tabpanel" aria-labelledby="pills-inactive-part-types-tab">
            <div class="subsection-info-block">
            <?= GridView::widget([
                'dataProvider' => $inactivePartTypesDataProvider,
                'filterModel' => $inactivePartTypesSearchModel,
                'columns' => [
                    [
                        'header' => 'Actions',
                        'class' => ActionColumn::class,
                        'urlCreator' => function ($action, PartType $model, $key, $index, $column) {
                            return Url::toRoute([$action, 'id' => $model->id]);
                        }
                    ],
                    'id',
                    'name',
                    'description',
                    'created_by' => [
                        'attribute' => 'created_by',
                        'value' => function (PartType $model) {
                            return Html::a($model->createdBy->username . Html::decode(' <i class="fa-solid fa-arrow-up-right-from-square"></i>'),
                                Url::toRoute(['/user/view', 'id' => $model->created_by]), ['target' => '_blank',]);
                        },
                        'format' => 'raw',
                        'enableSorting' => false,
                        'filter' => false,
                    ],
                    'created' => [
                        'attribute' => 'created',
                        'label' => 'Date Created',
                        'value' => function (PartType $model) {
                            return Yii::$app->dateUtils->asDate($model->created);
                        }
                    ],
                    'modified' => [
                        'attribute' => 'modified',
                        'label' => 'Date Modified',
                        'value' => function (PartType $model) {
                            return Yii::$app->dateUtils->asDate($model->modified);
                        }
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
