<?php

use app\models\User;
use yii\helpers\Html;
use yii\web\YiiAsset;
use app\models\JobType;
use app\models\Building;
use app\models\District;
use app\models\Division;
use app\models\JobStatus;
use app\models\TimeEntry;
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
                <div class="col-md-auto col-lg-4 | mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1rem" height="1rem" fill="currentColor" class="bi bi-person-raised-hand" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="M6 6.207v9.043a.75.75 0 0 0 1.5 0V10.5a.5.5 0 0 1 1 0v4.75a.75.75 0 0 0 1.5 0v-8.5a.25.25 0 1 1 .5 0v2.5a.75.75 0 0 0 1.5 0V6.5a3 3 0 0 0-3-3H6.236a1 1 0 0 1-.447-.106l-.33-.165A.83.83 0 0 1 5 2.488V.75a.75.75 0 0 0-1.5 0v2.083c0 .715.404 1.37 1.044 1.689L5.5 5c.32.32.5.754.5 1.207"/>
                        <path d="M8 3a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3"/>
                    </svg> 
                    <?= $model->requester ?>
                </div>
                <div class="col col-lg-4 | mb-2">
                    <?= '<a href="mailto:' . $model->requester_email . '">'?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="1rem" height="1rem" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16" aria-hidden="true">
                            <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414zM0 4.697v7.104l5.803-3.558zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586zm3.436-.586L16 11.801V4.697z"/>
                        </svg> 
                        <?= $model->requester_email ?>
                    </a>
                </div>
                <div class="col-md-auto col-lg-4 | mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1rem" height="1rem" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16" aria-hidden="true">
                        <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z"/>
                    </svg> 
                    <?php
                        // phone numbers don't have to be provided, if that's the case, say so
                        $phone_number = $model->requester_phone;
                        if ($phone_number != NULL) {
                            echo '<a href="Tel:' . $phone_number . '">' . $phone_number . '</a>';
                        } else {
                            echo 'Not provided';
                        };
                    ?>
                </div>
            </div>
        </div>
        <div class="quick-glance-segment">
            <p class="quick-glance-label"><?= $model->summary ?></p>
            <p class="apply-word-break"><?= $model->description ?></p>
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

                        $location_string = $division . ' > ' . $department . '<br>' . $department_building . '<br>"' . $requester_location . '"';
                    } elseif($model->customer_type_id == 2 || $model->customer_type_id == 4) {
                        // WNYRIC or District: get district, district building
                        $district = $model->district->name;
                        $district_building = Building::findOne(DistrictBuilding::findOne($model->district_building_id)->building_id)->name;

                        $location_string = $district . '<br>' . $district_building . '<br>"' . $requester_location . '"';
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
                    $icon_path = $model->jobCategory->icon_path;

                    echo '<img src="' . $icon_path . '" aria-hidden="true"><strong>' . $type . ':</strong> ' . $category;
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
        <div id="ticket-time-stats" class="d-flex flex-wrap justify-content-evenly">
            <div class="stat-box flex-fill">
                <p>
                    <svg xmlns="http://www.w3.org/2000/svg" width="1rem" height="1rem" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                    </svg>
                    Total Tech Time
                </p>
                <p id="total-ticket-tech-time" class="totaled-hours"><?= TimeEntry::getTotalTicketTimeFor($model->id, 'tech_time') ?> (hrs)</p>
            </div>
            <div class="stat-box flex-fill">
                <p>
                    <svg xmlns="http://www.w3.org/2000/svg" width="1rem" height="1rem" fill="currentColor" class="bi bi-alarm-fill" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="M6 .5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1H9v1.07a7.001 7.001 0 0 1 3.274 12.474l.601.602a.5.5 0 0 1-.707.708l-.746-.746A6.97 6.97 0 0 1 8 16a6.97 6.97 0 0 1-3.422-.892l-.746.746a.5.5 0 0 1-.707-.708l.602-.602A7.001 7.001 0 0 1 7 2.07V1h-.5A.5.5 0 0 1 6 .5m2.5 5a.5.5 0 0 0-1 0v3.362l-1.429 2.38a.5.5 0 1 0 .858.515l1.5-2.5A.5.5 0 0 0 8.5 9zM.86 5.387A2.5 2.5 0 1 1 4.387 1.86 8.04 8.04 0 0 0 .86 5.387M11.613 1.86a2.5 2.5 0 1 1 3.527 3.527 8.04 8.04 0 0 0-3.527-3.527"/>
                    </svg>
                    Total Overtime
                </p>
                <p id="total-ticket-overtime" class="totaled-hours"><?= TimeEntry::getTotalTicketTimeFor($model->id, 'overtime') ?> (hrs)</p>
            </div>
            <div class="stat-box flex-fill">
                <p>
                    <svg xmlns="http://www.w3.org/2000/svg" width="1rem" height="1rem" fill="currentColor" class="bi bi-car-front-fill" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="M2.52 3.515A2.5 2.5 0 0 1 4.82 2h6.362c1 0 1.904.596 2.298 1.515l.792 1.848c.075.175.21.319.38.404.5.25.855.715.965 1.262l.335 1.679q.05.242.049.49v.413c0 .814-.39 1.543-1 1.997V13.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-1.338c-1.292.048-2.745.088-4 .088s-2.708-.04-4-.088V13.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-1.892c-.61-.454-1-1.183-1-1.997v-.413a2.5 2.5 0 0 1 .049-.49l.335-1.68c.11-.546.465-1.012.964-1.261a.8.8 0 0 0 .381-.404l.792-1.848ZM3 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2m10 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2M6 8a1 1 0 0 0 0 2h4a1 1 0 1 0 0-2zM2.906 5.189a.51.51 0 0 0 .497.731c.91-.073 3.35-.17 4.597-.17s3.688.097 4.597.17a.51.51 0 0 0 .497-.731l-.956-1.913A.5.5 0 0 0 11.691 3H4.309a.5.5 0 0 0-.447.276L2.906 5.19Z"/>
                    </svg>
                    Total Travel Time
                </p>
                <p id="total-ticket-travel-time" class="totaled-hours"><?= TimeEntry::getTotalTicketTimeFor($model->id, 'travel_time') ?> (hrs)</p>
            </div>
            <div class="stat-box flex-fill">
                <p>
                    <svg xmlns="http://www.w3.org/2000/svg" width="1rem" height="1rem" fill="currentColor" class="bi bi-luggage-fill" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="M2 1.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V5h.5A1.5 1.5 0 0 1 8 6.5V7H7v-.5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 .5.5H4v1H2.5v.25a.75.75 0 0 1-1.5 0v-.335A1.5 1.5 0 0 1 0 13.5v-7A1.5 1.5 0 0 1 1.5 5H2zM3 5h2V2H3z"/>
                        <path d="M2.5 7a.5.5 0 0 1 .5.5v5a.5.5 0 0 1-1 0v-5a.5.5 0 0 1 .5-.5m10 1v-.5A1.5 1.5 0 0 0 11 6h-1a1.5 1.5 0 0 0-1.5 1.5V8H8v8h5V8zM10 7h1a.5.5 0 0 1 .5.5V8h-2v-.5A.5.5 0 0 1 10 7M5 9.5A1.5 1.5 0 0 1 6.5 8H7v8h-.5A1.5 1.5 0 0 1 5 14.5zm9 6.5V8h.5A1.5 1.5 0 0 1 16 9.5v5a1.5 1.5 0 0 1-1.5 1.5z"/>
                    </svg>
                    Total Itinerate Time
                </p>
                <p id="total-ticket-itinerate-time" class="totaled-hours"><?= TimeEntry::getTotalTicketTimeFor($model->id, 'itinerate_time') ?> (hrs)</p>
            </div>
            <div class="total-billable-hours-box stat-box flex-fill | fw-bold">
                <p>
                    <svg xmlns="http://www.w3.org/2000/svg" width="1rem" height="1rem" fill="currentColor" class="bi bi-clock-fill" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z"/>
                    </svg>
                    Total Billable Hours
                </p>
                <p id="total-ticket-hours" class="totaled-hours"><?= TimeEntry::getTotalTicketTimeFor($model->id, 'all') ?> (hrs)</p>
            </div>
        </div>

            <div class="quick-glance-info-line timestamp my-3 mx-2">
                <p>Ticket created:
                    <?php
                        echo date('m/d/Y, h:iA', strtotime($model->created));
                    ?>
                </p>
            </div>

    </div>
</div>