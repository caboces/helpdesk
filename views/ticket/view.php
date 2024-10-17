<?php

use app\models\User;
use yii\helpers\Html;
use yii\web\YiiAsset;
use app\models\JobType;
use app\models\Building;
use app\models\District;
use app\models\Division;
use app\models\JobStatus;
use app\models\Department;
use app\models\JobCategory;
use app\models\JobPriority;
use yii\widgets\DetailView;
use app\models\DistrictBuilding;
use app\models\DepartmentBuilding;
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
        <div class="quick-glance-segment">
            <p class="quick-glance-label"><?= $model->summary ?></p>
            <p><?= $model->description ?></p>
        </div>
        <div class="quick-glance-segment">
            <div class="quick-glance-info-line">
                <svg xmlns="http://www.w3.org/2000/svg" width="1rem" height="1rem" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16" aria-hidden="true">
                    <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                </svg>
                <p>
                    <?= $primary_tech = $model->primary_tech_id != NULL ? User::findOne($model->primary_tech_id)->username: 'Unassigned'; ?>
                </p>
            </div>
            <div class="quick-glance-info-line">
                <svg xmlns="http://www.w3.org/2000/svg" width="1rem" height="1rem" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16" aria-hidden="true">
                    <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                </svg>
                <p>
                    <?= $model->getUsers()->select('id', 'username')->column() != NULL ? $secondary_techs = implode(', ', $model->getUsers()->select('id', 'username')->column()) : 'No additional assigned techs';?>
                </p>
            </div>
            <div class="quick-glance-info-line">
                <svg xmlns="http://www.w3.org/2000/svg" width="1rem" height="1rem" fill="currentColor" class="bi bi-geo-fill" viewBox="0 0 16 16" aria-hidden="true">
                    <path fill-rule="evenodd" d="M4 4a4 4 0 1 1 4.5 3.969V13.5a.5.5 0 0 1-1 0V7.97A4 4 0 0 1 4 3.999zm2.493 8.574a.5.5 0 0 1-.411.575c-.712.118-1.28.295-1.655.493a1.3 1.3 0 0 0-.37.265.3.3 0 0 0-.057.09V14l.002.008.016.033a.6.6 0 0 0 .145.15c.165.13.435.27.813.395.751.25 1.82.414 3.024.414s2.273-.163 3.024-.414c.378-.126.648-.265.813-.395a.6.6 0 0 0 .146-.15l.015-.033L12 14v-.004a.3.3 0 0 0-.057-.09 1.3 1.3 0 0 0-.37-.264c-.376-.198-.943-.375-1.655-.493a.5.5 0 1 1 .164-.986c.77.127 1.452.328 1.957.594C12.5 13 13 13.4 13 14c0 .426-.26.752-.544.977-.29.228-.68.413-1.116.558-.878.293-2.059.465-3.34.465s-2.462-.172-3.34-.465c-.436-.145-.826-.33-1.116-.558C3.26 14.752 3 14.426 3 14c0-.599.5-1 .961-1.243.505-.266 1.187-.467 1.957-.594a.5.5 0 0 1 .575.411"/>
                </svg>
                <p>
                <?php
                    $location_string = '';
                    $requester_location = $model->location;

                    if($model->customer_type_id == 1) {
                        // BOCES: get division > department, department building
                        $division = $model->division->name;
                        $department = $model->department->name;
                        $department_building = Building::findOne(DepartmentBuilding::findOne($model->department_building_id)->building_id)->name;

                        $location_string = $division . ' > ' . $department . ', ' . $department_building . '<br>"' . $requester_location . '"';
                    } elseif($model->customer_type_id == 2 || $model->customer_type_id == 4) {
                        // WNYRIC or District: get district, district building
                        $district = $model->district->name;
                        $district_building = Building::findOne(DistrictBuilding::findOne($model->district_building_id)->building_id)->name;

                        $location_string = $district . ', ' . $district_building . '<br>"' . $requester_location . '"';
                    }
                    echo $location_string;
                ?>
                </p>
            </div>
        </div>
        <div class="quick-glance-segment">
            <div class="quick-glance-info-line">
                <?php
                    $type = $model->jobType->name;
                    $category = $model->jobCategory->name;
                    $priority = $model->jobPriority->name;
                    $status = $model->jobStatus->name;

                    echo '<strong>' . $type . ':</strong> ' . $category;
                ?>
            </div>
            <?php
                $job_priority = $model->jobPriority->name;
                $bgcolor = $model->jobPriority->color;
                echo '<span class="dot" style="background-color:' . $bgcolor . ' "></span>' . $job_priority . ' priority<br>';
            ?>
            <?php
                $job_status = $model->jobStatus->name;
                $bgcolor = $model->jobStatus->color;
                // want to add in a background color to these dots
                echo '<span class="dot" style="background-color:' . $bgcolor . ' "></span>' . $job_status;
            ?>
        </div>
        <div class="quick-glance-segment">
            <div class="quick-glance-info-line timestamp">
                <p>Ticket created:
                    <?php
                        echo date('m/d/Y, h:iA', strtotime($model->created));
                    ?>
                </p>
            </div>
            <div class="quick-glance-info-line timestamp">
                <p>Last modified: 
                    <?php
                        echo date('m/d/Y, h:iA', strtotime($model->modified));
                    ?>
                </p>
            </div>
        </div>
    </div>
</div>