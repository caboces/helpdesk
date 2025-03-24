<?php

use app\models\Department;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\DepartmentSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Department Management';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="department-index">
    
    <div class="title-icon d-flex align-items-center">
        <i class="fa-solid fa-compass fa-2xl"></i>
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <p>This is the Deparmtnent Management page. Create, update, view, or delete different CABOCES departments.</p>
    <!-- action buttons -->
    <div class='container-fluid p-2 | bg-dark shadow-sm'>
        <?= Html::a('Reset filters', ['index'], ['class' => 'btn btn-secondary']); ?>
        <?= Html::a(Html::decode('<i class="fa-solid fa-plus"></i>') . ' Create', ['create'], ['class' => 'btn btn-primary']) ?>
    </div>

    <!-- pill nav -->
    <ul class="nav nav-pills" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pills-active-departments-tab" data-bs-toggle="pill" data-bs-target="#pills-active-departments" type="button" role="tab" aria-controls="pills-active-departments" aria-selected="true">Active Departments</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-inactive-departments-tab" data-bs-toggle="pill" data-bs-target="#pills-inactive-departments" type="button" role="tab" aria-controls="pills-inactive-departments" aria-selected="false">Inactive Departments</button>
        </li>
    </ul>

    <!-- pill content -->
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-active-departments" role="tabpanel" aria-labelledby="pills-active-departments-tab">
            <div class="subsection-info-block">
                <h2>Active Departments</h2>
                <p>Active departments can be used when assigning and creating new tickets and ticket drafts.</p>
                <?php Pjax::begin(['id' => 'grid-active-departments']) ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        [
                            'header' => 'Actions',
                            'class' => ActionColumn::class,
                            'urlCreator' => function ($action, Department $model, $key, $index, $column) {
                                return Url::toRoute([$action, 'id' => $model->id]);
                            }
                        ],
                        'division_id' => [
                            'attribute' => 'division_id',
                            'label' => 'Division',
                            'value' => function (Department $model) {
                                return Html::a($model->division->name . Html::decode(' <i class="fa-solid fa-arrow-up-right-from-square"></i>'), Url::toRoute(['/division/view', 'id' => $model->division_id]), ['target' => '_blank', 'data-pjax' => 0]);
                            },
                            'format' => 'raw'
                        ],
                        'name',
                        'description',
                        'status',
                        'created' => [
                            'attribute' => 'created',
                            'label' => 'Date Created',
                            'value' => function (Department $model) {
                                return Yii::$app->dateUtils->asDate($model->created);
                            }
                        ],
                        'modified' => [
                            'attribute' => 'modified',
                            'label' => 'Date Modified',
                            'value' => function (Department $model) {
                                return Yii::$app->dateUtils->asDate($model->modified);
                            }
                        ],
                    ],
                ]); ?>
                <?php Pjax::end() ?>
            </div>
        </div>
        <div class="tab-pane fade" id="pills-inactive-departments" role="tabpanel" aria-labelledby="pills-inactive-departments-tab">
            <div class="subsection-info-block">
                <h2>Inactive Departments</h2>
                <p>Inactive departments have been deactivated and cannot be used when creating new tickets or ticket drafts.</p>
                <p>These departments still exist internally are not deleted, and will show up in reports dating back to when they were still active.</p>
                <?php Pjax::begin(['id' => 'grid-inactive-departments']) ?>
                <?= GridView::widget([
                    'dataProvider' => $inactiveDepartmentsDataProvider,
                    'filterModel' => $inactiveDepartmentsSearchModel,
                    'columns' => [
                        [
                            'header' => 'Actions',
                            'class' => ActionColumn::class,
                            'urlCreator' => function ($action, Department $model, $key, $index, $column) {
                                return Url::toRoute([$action, 'id' => $model->id]);
                            }
                        ],
                        'division_id' => [
                            'attribute' => 'division_id',
                            'label' => 'Division',
                            'value' => function (Department $model) {
                                return Html::a($model->division->name . Html::decode(' <i class="fa-solid fa-arrow-up-right-from-square"></i>'), Url::toRoute(['/division/view', 'id' => $model->division_id]), ['target' => '_blank', 'data-pjax' => 0]);
                            },
                            'format' => 'raw'
                        ],
                        'name',
                        'description',
                        'status',
                        'created' => [
                            'attribute' => 'created',
                            'label' => 'Date Created',
                            'value' => function (Department $model) {
                                return Yii::$app->dateUtils->asDate($model->created);
                            }
                        ],
                        'modified' => [
                            'attribute' => 'modified',
                            'label' => 'Date Modified',
                            'value' => function (Department $model) {
                                return Yii::$app->dateUtils->asDate($model->modified);
                            }
                        ],
                    ],
                ]); ?>
                <?php Pjax::end() ?>
            </div>
        </div>
    </div>


</div>
