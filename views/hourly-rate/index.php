<?php

use app\models\HourlyRate;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Hourly Rates Management';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hourly-rate-index">

    <div class="title-icon d-flex align-items-center">
        <i class="fa-solid fa-percent fa-2xl"></i>
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <p>This is the Hourly Rates Management page.</p>
    <p>You cannot update or remove hourly rates.</p>
    <!-- action buttons -->
    <div class='container-fluid p-2 | bg-dark shadow-sm'>
        <?= Html::a('Reset filters', ['index'], ['class' => 'btn btn-secondary']); ?>
        <?= Html::a(Html::decode('<i class="fa-solid fa-plus"></i>') . ' Create', ['/hourly-rate/create'], ['class' => 'btn btn-primary']) ?>
    </div>

    <!-- pill nav -->
    <ul class="nav nav-pills" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pills-active-hourly-rates-tab" data-bs-toggle="pill" data-bs-target="#pills-active-hourly-rates" type="button" role="tab" aria-controls="pills-active-hourly-rates" aria-selected="true">Active Rates</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-inactive-hourly-rates-tab" data-bs-toggle="pill" data-bs-target="#pills-inactive-hourly-rates" type="button" role="tab" aria-controls="pills-inactive-hourly-rates" aria-selected="false">Inactive Rates</button>
        </li>
    </ul>

    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-active-hourly-rates" role="tabpanel" aria-labelledby="pills-active-hourly-rates-tab">
            <div class="subsection-info-block">
            <h2>Active Hourly Rates</h2>
            <p>"Active" rates show rates that are applicable as of todays date (<em><?= date('F j, Y') ?></em>). "Inactive" rates show thats that are no longer applicable as of todays date.</p>
            <p>To see certain rates at specific times of the year, sort by the First Day Effective or the Last Day Effective.</p>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'header' => 'Actions',
                        'class' => ActionColumn::class,
                        'template' => '{view}',
                        'urlCreator' => function ($action, HourlyRate $model, $key, $index, $column) {
                            return Url::toRoute([$action, 'id' => $model->id]);
                        }
                    ],
                    'job_type_id' => [
                        'attribute' => 'job_type_id',
                        'label' => 'Job Type',
                        'value' => function (HourlyRate $model) {
                            return Html::a($model->jobType->name . Html::decode(' <i class="fa-solid fa-arrow-up-right-from-square"></i>'), 
                                Url::toRoute(['/job-type/view', 'id' => $model->job_type_id]), ['target' => '_blank']);
                        },
                        'format' => 'raw',
                        'enableSorting' => false,
                        'filter' => false,
                    ],
                    'rate',
                    'summer_rate',
                    'description',
                    'first_day_effective' => [
                        'attribute' => 'first_day_effective',
                        'label' => 'First Day Effective',
                        'value' => function (HourlyRate $model) {
                            return Yii::$app->dateUtils->asDate($model->first_day_effective);
                        }
                    ],
                    'last_day_effective' => [
                        'attribute' => 'last_day_effective',
                        'label' => 'Last Day Effective',
                        'value' => function (HourlyRate $model) {
                            return Yii::$app->dateUtils->asDate($model->last_day_effective);
                        }
                    ],
                ],
            ]); ?>
            </div>
        </div>
        <div class="tab-pane fade" id="pills-inactive-hourly-rates" role="tabpanel" aria-labelledby="pills-inactive-hourly-rates-tab">
            <div class="subsection-info-block">
            <h2>Inactive Hourly Rates</h2>
            <p>Inactive hourly rates are rates that are no longer valid due to being expired.</p>
            <?= GridView::widget([
                'dataProvider' => $inactiveHourlyRatesDataProvider,
                'columns' => [
                    [
                        'header' => 'Actions',
                        'class' => ActionColumn::class,
                        'template' => '{view}',
                        'urlCreator' => function ($action, HourlyRate $model, $key, $index, $column) {
                            return Url::toRoute([$action, 'id' => $model->id]);
                        }
                    ],
                    'job_type_id' => [
                        'attribute' => 'job_type_id',
                        'label' => 'Job Type',
                        'value' => function (HourlyRate $model) {
                            return Html::a($model->jobType->name . Html::decode(' <i class="fa-solid fa-arrow-up-right-from-square"></i>'), 
                                Url::toRoute(['/job-type/view', 'id' => $model->job_type_id]), ['target' => '_blank']);
                        },
                        'format' => 'raw',
                        'enableSorting' => false,
                        'filter' => false,
                    ],
                    'rate',
                    'summer_rate',
                    'description',
                    'first_day_effective' => [
                        'attribute' => 'first_day_effective',
                        'label' => 'First Day Effective',
                        'value' => function (HourlyRate $model) {
                            return Yii::$app->dateUtils->asDate($model->first_day_effective);
                        }
                    ],
                    'last_day_effective' => [
                        'attribute' => 'last_day_effective',
                        'label' => 'Last Day Effective',
                        'value' => function (HourlyRate $model) {
                            return Yii::$app->dateUtils->asDate($model->last_day_effective);
                        }
                    ],
                ],
            ]); ?>
            </div>
        </div>
    </div>
</div>
