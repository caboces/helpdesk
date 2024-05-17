<?php

use app\models\User;
use yii\helpers\Url;
use yii\widgets\Pjax;
use app\models\Ticket;
use yii\grid\GridView;
use app\models\JobType;
use yii\bootstrap5\Html;
use app\models\JobStatus;
use yii\grid\ActionColumn;
use app\models\JobCategory;
use app\models\JobPriority;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Ticket Management';

?>

<div class="ticket-index">

    <div class="title-icon d-flex align-items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="2rem" height="2rem" fill="currentColor" class="bi bi-ticket-detailed-fill" viewBox="0 0 16 16">
            <path d="M0 4.5A1.5 1.5 0 0 1 1.5 3h13A1.5 1.5 0 0 1 16 4.5V6a.5.5 0 0 1-.5.5 1.5 1.5 0 0 0 0 3 .5.5 0 0 1 .5.5v1.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 11.5V10a.5.5 0 0 1 .5-.5 1.5 1.5 0 1 0 0-3A.5.5 0 0 1 0 6zm4 1a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 0-1h-7a.5.5 0 0 0-.5.5m0 5a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 0-1h-7a.5.5 0 0 0-.5.5M4 8a1 1 0 0 0 1 1h6a1 1 0 1 0 0-2H5a1 1 0 0 0-1 1" />
        </svg>
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <p>You are viewing the Ticket Management page. From here you may filter, view, and edit existing tickets, or create new tickets.</p>

    <!-- action buttons -->
    <div class='container-fluid p-2 | bg-dark shadow-sm'>
        <?= Html::a('Reset filters', ['index'], ['class' => 'btn btn-secondary']); ?>
        <?= Html::a('New ticket', ['create'], ['class' => 'btn btn-primary bg-pacific-cyan border-pacific-cyan']) ?>
    </div>

    <!-- pill nav -->
    <ul class="nav nav-pills" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pills-assignments-tab" data-bs-toggle="pill" data-bs-target="#pills-assignments" type="button" role="tab" aria-controls="pills-assignments" aria-selected="true">Assignments</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-all-tab" data-bs-toggle="pill" data-bs-target="#pills-all" type="button" role="tab" aria-controls="pills-all" aria-selected="false">All tickets</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-resolved-closed-tab" data-bs-toggle="pill" data-bs-target="#pills-resolved-closed" type="button" role="tab" aria-controls="pills-resolved-closed" aria-selected="false">Resolved / Closed</button>
        </li>
    </ul>

    <!-- pill content -->
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-assignments" role="tabpanel" aria-labelledby="pills-assignments-tab">
            <div class="subsection-info-block">
                <h2>Assigned tickets</h2>
                <p>All tickets currently assigned to this user</p>
                <div class="alert alert-warning" role="alert">
                    Filters haven't been made yet! Currently showing all tickets.
                </div>
                <div class="table-container container-fluid overflow-x-scroll">
                    <?php Pjax::begin(['id' => 'grid-assignments']); ?>
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'tableOptions' => ['class' => 'table table-bordered'],
                        'rowOptions' => function ($model) {
                            if ($model->jobPriority->name == 'Critical') {
                                return ['class' => 'critical'];
                            } else if ($model->jobPriority->name == 'High') {
                                return ['class' => 'high'];
                            }
                            return [];
                        },
                        'columns' => [
                            [
                                'class' => ActionColumn::className(),
                                'buttons' => [
                                    'images' => function ($url, $model, $key) { // <--- here you can override or create template for a button of a given name
                                        return Html::a('<span class="glyphicon glyphicon glyphicon-picture" aria-hidden="true"></span>', Url::to(['image/index', 'id' => $model->id]));
                                    }
                                ],
                                'urlCreator' => function ($action, Ticket $model, $key, $index, $column) {
                                    return Url::toRoute([$action, 'id' => $model->id]);
                                }
                            ],
                            'id' => [
                                'attribute' => 'id',
                                'filter' => false,
                            ],
                            'summary',
                            'requester',
                            'primary_tech_id' => [
                                'label' => 'Primary Tech',
                                'attribute' => 'primary_tech_id',
                                'value' => function($data) {
                                    return ($data->primaryTech != null ? $data->primaryTech->username : '');
                                }
                            ],
                            'location',
                            'job_category_name' => [
                                'attribute' => 'job_category_name',
                                'value' => 'jobCategory.name',
                                'format' => 'text',
                                'label' => 'Category',
                                'filter' => Html::activeDropDownList($searchModel, 'job_category_name', $categories, ['class' => 'form-control', 'prompt' => '-All-']),
                            ],
                            'job_priority_name' => [
                                'attribute' => 'job_priority_name',
                                'value' => 'jobPriority.name',
                                'format' => 'text',
                                'label' => 'Priority',
                                'filter' => Html::activeDropDownList($searchModel, 'job_priority_name', $priorities, ['class' => 'form-control', 'prompt' => '-All-']),
                            ],
                            'job_status_name' => [
                                'attribute' => 'job_status_name',
                                'value' => 'jobStatus.name',
                                'format' => 'text',
                                'label' => 'Status',
                                'filter' => Html::activeDropDownList($searchModel, 'job_status_name', $statuses, ['class' => 'form-control', 'prompt' => '-All-']),
                            ],
                            'job_type_name' => [
                                'attribute' => 'job_type_name',
                                'value' => 'jobType.name',
                                'format' => 'text',
                                'label' => 'Type',
                                'filter' => Html::activeDropDownList($searchModel, 'job_type_name', $types, ['class' => 'form-control', 'prompt' => '-All-']),
                            ],
                            'created' => [
                                'attribute' => 'created',
                                'label' => 'Date submitted',
                                'filter' => false,
                            ],
                        ],
                    ]); ?>
                    <?php Pjax::end(); ?>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="pills-all" role="tabpanel" aria-labelledby="pills-all-tab">
            <div class="subsection-info-block">
                <h2>All tickets</h2>
                <p>All tickets in the current workflow</p>
                <div class="container-fluid overflow-x-scroll">
                    <?php Pjax::begin(['id' => 'grid-all']); ?>
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'tableOptions' => ['class' => 'table table-bordered'],
                        'rowOptions' => function ($model) {
                            if ($model->jobPriority->name == 'Critical') {
                                return ['class' => 'critical'];
                            }
                            return [];
                        },
                        'columns' => [
                            [
                                'class' => ActionColumn::className(),
                                'buttons' => [
                                    'images' => function ($url, $model, $key) { // <--- here you can override or create template for a button of a given name
                                        return Html::a('<span class="glyphicon glyphicon glyphicon-picture" aria-hidden="true"></span>', Url::to(['image/index', 'id' => $model->id]));
                                    }
                                ],
                                'urlCreator' => function ($action, Ticket $model, $key, $index, $column) {
                                    return Url::toRoute([$action, 'id' => $model->id]);
                                }
                            ],
                            'id' => [
                                'attribute' => 'id',
                                'filter' => false,
                            ],
                            'summary',

                            /** 
                             * TODO: we need to have LDAP before we can fill in the user fields automatically.
                             * Location is up in the air but i'm working on it.. -efox
                             */
                            'requester',
                            'primary_tech',
                            'location',
                            'job_category_name' => [
                                'attribute' => 'job_category_name',
                                'value' => 'jobCategory.name',
                                'format' => 'text',
                                'label' => 'Category',
                                'filter' => Html::activeDropDownList($searchModel, 'job_category_name', $categories, ['class' => 'form-control', 'prompt' => '-All-']),
                            ],
                            'job_priority_name' => [
                                'attribute' => 'job_priority_name',
                                'value' => 'jobPriority.name',
                                'format' => 'text',
                                'label' => 'Priority',
                                'filter' => Html::activeDropDownList($searchModel, 'job_priority_name', $priorities, ['class' => 'form-control', 'prompt' => '-All-']),
                            ],
                            'job_status_name' => [
                                'attribute' => 'job_status_name',
                                'value' => 'jobStatus.name',
                                'format' => 'text',
                                'label' => 'Status',
                                'filter' => Html::activeDropDownList($searchModel, 'job_status_name', $statuses, ['class' => 'form-control', 'prompt' => '-All-']),
                            ],
                            'job_type_name' => [
                                'attribute' => 'job_type_name',
                                'value' => 'jobType.name',
                                'format' => 'text',
                                'label' => 'Type',
                                'filter' => Html::activeDropDownList($searchModel, 'job_type_name', $types, ['class' => 'form-control', 'prompt' => '-All-']),
                            ],
                            'created' => [
                                'attribute' => 'created',
                                'label' => 'Date submitted',
                                'filter' => false,
                            ],
                        ],
                    ]); ?>
                    <?php Pjax::end(); ?>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="pills-resolved-closed" role="tabpanel" aria-labelledby="pills-resolved-closed-tab">
            <div class="subsection-info-block">
                <h2>Resolved / Closed tickets</h2>
                <p>All tickets that have been resolved / closed</p>
                <div class="alert alert-warning" role="alert">
                    Filters haven't been made yet! Currently showing all tickets.
                </div>
                <div class="container-fluid overflow-x-scroll">
                    <?php Pjax::begin(['id' => 'grid-resolved-closed']); ?>
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'tableOptions' => ['class' => 'table table-bordered'],
                        'rowOptions' => function ($model) {
                            if ($model->jobPriority->name == 'Critical') {
                                return ['class' => 'critical'];
                            } else if ($model->jobPriority->name == 'High') {
                                return ['class' => 'high'];
                            }
                            return [];
                        },
                        'columns' => [
                            [
                                'class' => ActionColumn::className(),
                                'buttons' => [
                                    'images' => function ($url, $model, $key) { // <--- here you can override or create template for a button of a given name
                                        return Html::a('<span class="glyphicon glyphicon glyphicon-picture" aria-hidden="true"></span>', Url::to(['image/index', 'id' => $model->id]));
                                    }
                                ],
                                'urlCreator' => function ($action, Ticket $model, $key, $index, $column) {
                                    return Url::toRoute([$action, 'id' => $model->id]);
                                }
                            ],
                            'id' => [
                                'attribute' => 'id',
                                'filter' => false,
                            ],
                            'summary',

                            /** 
                             * TODO: we need to have LDAP before we can fill in the user fields automatically.
                             * Location is up in the air but i'm working on it.. -efox
                             */
                            'requester',
                            'primary_tech',
                            'location',
                            'job_category_name' => [
                                'attribute' => 'job_category_name',
                                'value' => 'jobCategory.name',
                                'format' => 'text',
                                'label' => 'Category',
                                'filter' => Html::activeDropDownList($searchModel, 'job_category_name', $categories, ['class' => 'form-control', 'prompt' => '-All-']),
                            ],
                            'job_priority_name' => [
                                'attribute' => 'job_priority_name',
                                'value' => 'jobPriority.name',
                                'format' => 'text',
                                'label' => 'Priority',
                                'filter' => Html::activeDropDownList($searchModel, 'job_priority_name', $priorities, ['class' => 'form-control', 'prompt' => '-All-']),
                            ],
                            'job_status_name' => [
                                'attribute' => 'job_status_name',
                                'value' => 'jobStatus.name',
                                'format' => 'text',
                                'label' => 'Status',
                                'filter' => Html::activeDropDownList($searchModel, 'job_status_name', $statuses, ['class' => 'form-control', 'prompt' => '-All-']),
                            ],
                            'job_type_name' => [
                                'attribute' => 'job_type_name',
                                'value' => 'jobType.name',
                                'format' => 'text',
                                'label' => 'Type',
                                'filter' => Html::activeDropDownList($searchModel, 'job_type_name', $types, ['class' => 'form-control', 'prompt' => '-All-']),
                            ],
                            'created' => [
                                'attribute' => 'created',
                                'label' => 'Date submitted',
                                'filter' => false,
                            ],
                        ],
                    ]); ?>
                    <?php Pjax::end(); ?>
                </div>
            </div>
        </div>
    </div>

</div>