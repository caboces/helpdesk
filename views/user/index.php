<?php

use app\models\User;
use yii\helpers\Url;
use yii\bootstrap5\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\grid\ActionColumn;

/** @var yii\web\View $this */
/** @var app\models\UserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Manage Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <div class="title-icon d-flex align-items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="2rem" height="2rem" fill="currentColor" class="bi bi-gear-fill" viewBox="0 0 16 16">
            <path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z" />
        </svg>
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <!-- action buttons -->
    <div class='container-fluid p-2 | bg-dark shadow-sm'>
        <?= Html::a('Reset filters', ['index'], ['class' => 'btn btn-secondary']); ?>
        <?= Html::a('Create', ['site/signup'], ['class' => 'btn btn-primary bg-pacific-cyan border-pacific-cyan']) ?>
    </div>

    <!-- pill nav -->
    <ul class="nav nav-pills" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pills-all-tab" data-bs-toggle="pill" data-bs-target="#pills-all" type="button" role="tab" aria-controls="pills-all" aria-selected="true">All users</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-active-tab" data-bs-toggle="pill" data-bs-target="#pills-active" type="button" role="tab" aria-controls="pills-active" aria-selected="false">Active users</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-inactive-tab" data-bs-toggle="pill" data-bs-target="#pills-inactive" type="button" role="tab" aria-controls="pills-inactive" aria-selected="false">Inactive users</button>
        </li>
    </ul>

    <!-- pill content -->
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-all" role="tabpanel" aria-labelledby="pills-all-tab">
            <div class="subsection-info-block">
                <h2>All users</h2>
                <p>All helpdesk users</p>
                <div class="container-fluid overflow-x-scroll">
                    <?php Pjax::begin(['id' => 'grid-all']); ?>
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            [
                                'class' => ActionColumn::className(),
                                'template' => '{view} {update} {toggle-status}',
                                'buttons' => [
                                    'toggle-status' => function ($url, $model) {
                                        if ($model->status === $model::STATUS_INACTIVE) {
                                            return Html::a('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#09a2c9" class="bi bi-toggle-off" viewBox="0 0 16 16">
                                                                <path d="M11 4a4 4 0 0 1 0 8H8a5 5 0 0 0 2-4 5 5 0 0 0-2-4zm-6 8a4 4 0 1 1 0-8 4 4 0 0 1 0 8M0 8a5 5 0 0 0 5 5h6a5 5 0 0 0 0-10H5a5 5 0 0 0-5 5"/>
                                                            </svg>', $url, [
                                                'title' => "Activate",
                                                'class' => '',
                                                'data' => [
                                                    'method' => 'post',
                                                    'confirm' => 'Danger! Are you sure? This user will be reactivated.',
                                                ],
                                            ]);
                                        }
                                        return Html::a('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#09a2c9" class="bi bi-toggle-on" viewBox="0 0 16 16">
                                                            <path d="M5 3a5 5 0 0 0 0 10h6a5 5 0 0 0 0-10zm6 9a4 4 0 1 1 0-8 4 4 0 0 1 0 8"/>
                                                        </svg>', $url, [
                                            'title' => "Deactivate",
                                            'class' => '',
                                            'data' => [
                                                'method' => 'post',
                                                'confirm' => 'Danger! Are you sure? This user will be deactivated.',
                                            ],
                                        ]);
                                    }
                                ],
                            ],

                            'id' => [
                                'attribute' => 'id',
                                'filter' => false,
                            ],
                            'username',
                            'fname' => [
                                'attribute' => 'fname',
                                'label' => 'First name',
                            ],
                            'lname' => [
                                'attribute' => 'lname',
                                'label' => 'Last name'
                            ],
                            'email' => [
                                'attribute' => 'email',
                            ],
                            // 'status',
                        ],
                        'tableOptions' => ['class' => 'table table-bordered'],
                    ]); ?>
                    <?php Pjax::end(); ?>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="pills-active" role="tabpanel" aria-labelledby="pills-active-tab">
            <div class="subsection-info-block">
                <h2>Active users</h2>
                <p>All users that currently have access to the helpdesk</p>
                <?php Pjax::begin(['id' => 'grid-active']); ?>
                <?= GridView::widget([
                    'dataProvider' => $userActiveDataProvider,
                    'filterModel' => $userActiveSearchModel,
                    'columns' => [
                        [
                            'class' => ActionColumn::className(),
                            'template' => '{view} {update} {toggle-status} {delete}',
                            'buttons' => [
                                'toggle-status' => function ($url, $model) {
                                    if ($model->status === $model::STATUS_INACTIVE) {
                                        return Html::a('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#09a2c9" class="bi bi-toggle-off" viewBox="0 0 16 16">
                                                                <path d="M11 4a4 4 0 0 1 0 8H8a5 5 0 0 0 2-4 5 5 0 0 0-2-4zm-6 8a4 4 0 1 1 0-8 4 4 0 0 1 0 8M0 8a5 5 0 0 0 5 5h6a5 5 0 0 0 0-10H5a5 5 0 0 0-5 5"/>
                                                            </svg>', $url, [
                                            'title' => "Activate",
                                            'class' => '',
                                            'data' => [
                                                'method' => 'post',
                                                'confirm' => 'Danger! Are you sure? This user will be reactivated.',
                                            ],
                                        ]);
                                    }
                                    return Html::a('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#09a2c9" class="bi bi-toggle-on" viewBox="0 0 16 16">
                                                            <path d="M5 3a5 5 0 0 0 0 10h6a5 5 0 0 0 0-10zm6 9a4 4 0 1 1 0-8 4 4 0 0 1 0 8"/>
                                                        </svg>', $url, [
                                        'title' => "Deactivate",
                                        'class' => '',
                                        'data' => [
                                            'method' => 'post',
                                            'confirm' => 'Danger! Are you sure? This user will be deactivated.',
                                        ],
                                    ]);
                                }
                            ],
                        ],

                        'id' => [
                            'attribute' => 'id',
                            'filter' => false,
                        ],
                        'username',
                        'fname' => [
                            'attribute' => 'fname',
                            'label' => 'First name',
                        ],
                        'lname' => [
                            'attribute' => 'lname',
                            'label' => 'Last name'
                        ],
                        'email' => [
                            'attribute' => 'email',
                        ],
                        // 'status',
                    ],
                    'tableOptions' => ['class' => 'table table-bordered'],
                ]); ?>
                <?php Pjax::end(); ?>
            </div>
        </div>
        <div class="tab-pane fade" id="pills-inactive" role="tabpanel" aria-labelledby="pills-inactive">
            <div class="subsection-info-block">
                <h2>Inactive users</h2>
                <p>All users that currently do not have access to the helpdesk</p>
                <?php Pjax::begin(['id' => 'grid-inactive']); ?>
                <?= GridView::widget([
                    'dataProvider' => $userInactiveDataProvider,
                    'filterModel' => $userInactiveSearchModel,
                    'columns' => [
                        [
                            'class' => ActionColumn::className(),
                            'template' => '{view} {update} {toggle-status} {delete}',
                            'buttons' => [
                                'toggle-status' => function ($url, $model) {
                                    if ($model->status === $model::STATUS_INACTIVE) {
                                        return Html::a('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#09a2c9" class="bi bi-toggle-off" viewBox="0 0 16 16">
                                                                <path d="M11 4a4 4 0 0 1 0 8H8a5 5 0 0 0 2-4 5 5 0 0 0-2-4zm-6 8a4 4 0 1 1 0-8 4 4 0 0 1 0 8M0 8a5 5 0 0 0 5 5h6a5 5 0 0 0 0-10H5a5 5 0 0 0-5 5"/>
                                                            </svg>', $url, [
                                            'title' => "Activate",
                                            'class' => '',
                                            'data' => [
                                                'method' => 'post',
                                                'confirm' => 'Danger! Are you sure? This user will be reactivated.',
                                            ],
                                        ]);
                                    }
                                    return Html::a('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#09a2c9" class="bi bi-toggle-on" viewBox="0 0 16 16">
                                                            <path d="M5 3a5 5 0 0 0 0 10h6a5 5 0 0 0 0-10zm6 9a4 4 0 1 1 0-8 4 4 0 0 1 0 8"/>
                                                        </svg>', $url, [
                                        'title' => "Deactivate",
                                        'class' => '',
                                        'data' => [
                                            'method' => 'post',
                                            'confirm' => 'Danger! Are you sure? This user will be deactivated.',
                                        ],
                                    ]);
                                }
                            ],
                        ],

                        'id' => [
                            'attribute' => 'id',
                            'filter' => false,
                        ],
                        'username',
                        'fname' => [
                            'attribute' => 'fname',
                            'label' => 'First name',
                        ],
                        'lname' => [
                            'attribute' => 'lname',
                            'label' => 'Last name'
                        ],
                        'email' => [
                            'attribute' => 'email',
                        ],
                        // 'status',
                    ],
                    'tableOptions' => ['class' => 'table table-bordered'],
                ]); ?>
                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>


</div>