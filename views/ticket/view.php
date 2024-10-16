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
    <div class='container-fluid primary-action-button-bar'>
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

    <div class="quick-glance">
        <div class="quick-glance-segment">
            <p class="quick-glance-label"><?= $model->summary ?></p>
            <p><?= $model->description ?></p>
        </div>
        <div class="quick-glance-contact-info | container-fluid">
            <div class="row">
                <div class="col-md-auto col-lg-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1rem" height="1rem" fill="currentColor" class="bi bi-person-raised-hand" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="M6 6.207v9.043a.75.75 0 0 0 1.5 0V10.5a.5.5 0 0 1 1 0v4.75a.75.75 0 0 0 1.5 0v-8.5a.25.25 0 1 1 .5 0v2.5a.75.75 0 0 0 1.5 0V6.5a3 3 0 0 0-3-3H6.236a1 1 0 0 1-.447-.106l-.33-.165A.83.83 0 0 1 5 2.488V.75a.75.75 0 0 0-1.5 0v2.083c0 .715.404 1.37 1.044 1.689L5.5 5c.32.32.5.754.5 1.207"/>
                        <path d="M8 3a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3"/>
                    </svg> 
                    <?= $model->requester ?>
                </div>
                <div class="col col-lg-4">
                    <?= '<a href="mailto:' . $model->requester_email . '">'?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="1rem" height="1rem" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16" aria-hidden="true">
                            <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414zM0 4.697v7.104l5.803-3.558zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586zm3.436-.586L16 11.801V4.697z"/>
                        </svg> 
                        <?= $model->requester_email ?>
                    </a>
                </div>
                <div class="col-md-auto col-lg-4">
                    <?= '<a href="Tel:' . $model->requester_phone . '">'?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="1rem" height="1rem" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16" aria-hidden="true">
                            <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z"/>
                        </svg> 
                        <?= $model->requester_phone ?>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- detail widget for general details -->
    <div class="detail-view-container">
        <div class="table-container">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
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
                        'attribute' => 'jobType.name',
                        'label' => 'Type'
                    ],
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