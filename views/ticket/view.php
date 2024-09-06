<?php

use app\models\User;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\TechTicketAssignment;

/** @var yii\web\View $this */
/** @var app\models\Ticket $model */

$this->title = 'View Ticket: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Tickets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="ticket-view">

    <div class="title-icon d-flex align-items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="2rem" height="2rem" fill="currentColor" class="bi bi-binoculars-fill" viewBox="0 0 16 16" aria-hidden="true">
            <path d="M4.5 1A1.5 1.5 0 0 0 3 2.5V3h4v-.5A1.5 1.5 0 0 0 5.5 1zM7 4v1h2V4h4v.882a.5.5 0 0 0 .276.447l.895.447A1.5 1.5 0 0 1 15 7.118V13H9v-1.5a.5.5 0 0 1 .146-.354l.854-.853V9.5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v.793l.854.853A.5.5 0 0 1 7 11.5V13H1V7.118a1.5 1.5 0 0 1 .83-1.342l.894-.447A.5.5 0 0 0 3 4.882V4zM1 14v.5A1.5 1.5 0 0 0 2.5 16h3A1.5 1.5 0 0 0 7 14.5V14zm8 0v.5a1.5 1.5 0 0 0 1.5 1.5h3a1.5 1.5 0 0 0 1.5-1.5V14zm4-11H9v-.5A1.5 1.5 0 0 1 10.5 1h1A1.5 1.5 0 0 1 13 2.5z" />
        </svg>
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <p>You are currently viewing all of the details and recent activity of this ticket. To make modifications, click the "Update" button.</p>

    <!-- action buttons -->
    <div class='container-fluid p-2 | bg-dark shadow-sm'>
        <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary']); ?>
        <?= Html::a('Update ticket', ['update', 'id' => $model->id], ['class' => 'btn btn-primary bg-pacific-cyan border-pacific-cyan']) ?>
        <?= Html::a('Delete ticket', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger float-end',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </div>

    <!-- detail widget for general details -->
    <div class="detail-view-container">
        <div class="table-container">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'summary',
                    'description' => [
                        'attribute' => 'description',
                        'contentOptions' => ['class' => 'apply-word-break']
                    ],
                    // show the primary tech
                    [
                        'label' => 'Primary Technician',
                        // if there is an assigned primary tech, display their username
                        'value' => (User::findOne($model->primary_tech_id) != null ? User::findOne($model->primary_tech_id)->username : null),
                    ],
                    // show the assigned techs (username)
                    [
                        'label' => 'All Assigned Technicians',
                        'value' => implode(', ', $model->getUsers()
                            ->select('id', 'username')
                            ->column())
                    ],
                ],
            ])?>
        </div>
    </div>

    <!-- detail widget for customer/requester details -->
    <div class="detail-view-container">
        <h3>Customer and Requester</h3>
        <div class="table-container">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    // customer
                    [
                        'attribute' => 'customerType.name',
                        'label' => 'Customer Type',
                    ],
                    [
                        'attribute' => 'district.name',
                        'label' => 'District',
                        'visible' => !empty($model->district)
                    ],
                    [
                        'attribute' => 'division.name',
                        'label' => 'Division',
                        'visible' => !empty($model->division)
                    ],
                    [
                        'attribute' => 'department.name',
                        'label' => 'Department',
                        'visible' => !empty($model->department)
                    ],
                    [
                        'attribute' => 'departmentBuilding.buildingName',
                        'label' => 'Department Building',
                        'visible' => !empty($model->departmentBuilding)
                    ],
                    [
                        'attribute' => 'districtBuilding.buildingName',
                        'label' => 'District Building',
                        'visible' => !empty($model->districtBuilding)
                    ],
                    // requester
                    'requester',
                    'requester_email',
                    'requester_phone',
                    'location',
                ],
            ])?>
        </div>
    </div>

    <!-- detail widget for ticket tag details -->
    <div class="detail-view-container">
        <h3>Ticket Tags</h3>
        <div class="table-container">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    // ticket tags
                    [
                        'attribute' => 'jobCategory.name',
                        'label' => 'Category'
                    ],
                    [
                        'attribute' => 'jobPriority.name',
                        'label' => 'Priority'
                    ],
                    [
                        'attribute' => 'jobStatus.name',
                        'label' => 'Status'
                    ],
                    [
                        'attribute' => 'jobType.name',
                        'label' => 'Type'
                    ],
                ],
            ])?>
        </div>
    </div>

    <div class="detail-view-container">
        <h3>Creation / Modification Dates</h3>
        <div class="table-container">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'created',
                    'modified',
                ],
            ]) ?>
        </div>
    </div>

</div>