<?php

use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\User $model */

$this->title = 'View User: ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <div class="title-icon d-flex align-items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="2rem" height="2rem" fill="currentColor" class="bi bi-binoculars-fill" viewBox="0 0 16 16" aria-hidden="true">
            <path d="M4.5 1A1.5 1.5 0 0 0 3 2.5V3h4v-.5A1.5 1.5 0 0 0 5.5 1zM7 4v1h2V4h4v.882a.5.5 0 0 0 .276.447l.895.447A1.5 1.5 0 0 1 15 7.118V13H9v-1.5a.5.5 0 0 1 .146-.354l.854-.853V9.5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v.793l.854.853A.5.5 0 0 1 7 11.5V13H1V7.118a1.5 1.5 0 0 1 .83-1.342l.894-.447A.5.5 0 0 0 3 4.882V4zM1 14v.5A1.5 1.5 0 0 0 2.5 16h3A1.5 1.5 0 0 0 7 14.5V14zm8 0v.5a1.5 1.5 0 0 0 1.5 1.5h3a1.5 1.5 0 0 0 1.5-1.5V14zm4-11H9v-.5A1.5 1.5 0 0 1 10.5 1h1A1.5 1.5 0 0 1 13 2.5z" />
        </svg>
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <!-- action buttons -->
    <div class='container-fluid p-2 | bg-dark shadow-sm'>
        <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary']); ?>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary bg-pacific-cyan border-pacific-cyan text-dark']) ?>
        <div class="float-end">
            <!-- this is cheating, find a different way to do this -->
            <?= ($model->status === $model::STATUS_INACTIVE) ?
                Html::a('Activate user', ['toggle-status', 'id' => $model->id], [
                    'class' => 'btn btn-warning',
                    'data' => [
                        'method' => 'post',
                        'confirm' => 'Danger! Are you sure? This user will be reactivated.',
                    ],
                ])
                : Html::a('Deactivate user', ['toggle-status', 'id' => $model->id], [
                    'class' => 'btn btn-warning',
                    'data' => [
                        'method' => 'post',
                        'confirm' => 'Danger! Are you sure? This user will be deactivated.',
                    ],
                ]) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        </div>
    </div>
    <!-- detail widget for general details -->
    <div class="detail-view-container">
        <div class="table-container">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'username',
                    'fname',
                    'lname',
                    'email:email',
                    'status' => [
                        'attribute' => 'status',
                        'value' => function($model) {
                            $status = '';
                            switch($model->status) {
                                case 10:
                                    $status = 'Active';
                                    break;
                                case 9:
                                    $status = 'Inactive';
                                    break;
                                default:
                                    $status = 'Unknown';
                                    break;
                            }

                            return $status;
                        }
                    ],
                    'created_at:date',
                    'updated_at:date',
                ],
            ]) ?>
        </div>
    </div>

    <div class="detail-view-container">
        <h3>Current Assignments</h3>
        <div class="table-container">
            <?php
            GridView::widget([
                'dataProvider' => $currentUserAssignmentsProvider,
                'columns' => $ticketColumns,
            ]); ?>
        </div>
    </div>

    <div class="detail-view-container">
        <h3>Past Assignments</h3>
        <div class="table-container">
            <?php
            GridView::widget([
                'dataProvider' => $pastUserAssignmentsProvider,
                'columns' => $ticketColumns,
            ]); ?>
        </div>
    </div>
</div>