<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\models\Ticket;
use yii\grid\GridView;
use app\models\TimeEntry;
use yii\grid\ActionColumn;
/** @var yii\web\View $this */
/** @var app\models\TimeEntrySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Time Entries';
?>
<div class="time-entry-index">

    <div class="title-icon d-flex align-items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="2rem" height="2rem" fill="currentColor" class="bi bi-ticket-detailed-fill" viewBox="0 0 16 16">
            <path d="M0 4.5A1.5 1.5 0 0 1 1.5 3h13A1.5 1.5 0 0 1 16 4.5V6a.5.5 0 0 1-.5.5 1.5 1.5 0 0 0 0 3 .5.5 0 0 1 .5.5v1.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 11.5V10a.5.5 0 0 1 .5-.5 1.5 1.5 0 1 0 0-3A.5.5 0 0 1 0 6zm4 1a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 0-1h-7a.5.5 0 0 0-.5.5m0 5a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 0-1h-7a.5.5 0 0 0-.5.5M4 8a1 1 0 0 0 1 1h6a1 1 0 1 0 0-2H5a1 1 0 0 0-1 1" />
        </svg>
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <p>You are viewing the Time Entry Management page. From here you may filter, view, and edit existing time entries, or create new time entries.</p>

    <!-- action buttons -->
    <div class='container-fluid p-2 | bg-dark shadow-sm'>
        <?= Html::a('Reset filters', ['index'], ['class' => 'btn btn-secondary']); ?>
        <?= Html::a(Html::encode('<i class="fa-solid fa-clock"></i>') . ' New time entry', ['create'], ['class' => 'btn btn-primary bg-pacific-cyan border-pacific-cyan']) ?>
    </div>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="subsection-info-block">
        <h2>Time entries</h2>
        <p>All time entries</p>
    </div>
    <div class="table-container container-fluid overflow-x-scroll">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'tableOptions' => ['class' => 'table table-bordered'],
            'columns' => [
                [
                    'header' => 'Actions',
                    'class' => ActionColumn::class,
                    'buttons' => [
                        'images' => function ($url, $model, $key) { // <--- here you can override or create template for a button of a given name
                            return Html::a('<span class="glyphicon glyphicon glyphicon-picture" aria-hidden="true"></span>', Url::to(['image/index', 'id' => $model->id]));
                        }
                    ],
                    'urlCreator' => function ($action, TimeEntry $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'id' => $model->id]);
                    }
                ],
                'id',
                'tech_time',
                'overtime',
                'travel_time',
                'itinerate_time',
                'entry_date',
                'user_id',
                'ticket_id',
                'created',
                [
                    'attribute' => 'last_modified_by',
                    'value' => function($model) {
                            return '<a href="/user/view?id=' . ($model->last_modified_by_user_id != null ? $model->last_modified_by_user_id : '') . '">' . ($model->last_modified_by_user_id != null ? $model->lastModifiedBy->username : '') . '</a>';
                    },
                    'format' => 'raw',
                ],
            ],
        ]); ?>
    </div>

    <?php Pjax::end(); ?>

</div>
