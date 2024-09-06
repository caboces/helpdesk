<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\models\Ticket;
use yii\grid\GridView;
use app\models\TimeEntry;
use yii\bootstrap5\Modal;
use yii\grid\ActionColumn;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Ticket $model */
/** @var yii\bootstrap5\ActiveForm $form */
?>

<div class="ticket-form">
        <!-- modal window for time entries -->
        <?php 
        Modal::begin([
                'title' => 'Add Times',
                'id' => 'time-entry-modal',
                'size' => 'modal-lg',
        ]);

        echo '<div id="time-entry-modal-content"></div>';

        Modal::end(); 
        ?>

        <?php $form = ActiveForm::begin(); ?>

        <!-- action buttons -->
        <div class='container-fluid p-2 | bg-dark shadow-sm'>
                <?= Html::a('Back', Yii::$app->request->referrer, ['class' => 'btn btn-secondary']); ?>
                <?= Html::button('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-journal-plus" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M8 5.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V10a.5.5 0 0 1-1 0V8.5H6a.5.5 0 0 1 0-1h1.5V6a.5.5 0 0 1 .5-.5"/>
                                <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2"/>
                                <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z"/>
                        </svg> New tech note', [
                        'class' => 'btn btn-primary bg-iris border-iris',
                        'data-bs-toggle' => 'collapse',
                        'data-bs-target' => '#tech-note',
                        'aria-expanded' => 'false',
                        'aria-controls' => '#tech-note',
                ]); ?>
                <?= Html::button('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock" viewBox="0 0 16 16">
                                <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z"/>
                                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0"/>
                        </svg> New time entry', [
                        'value' => Url::to('/time-entry/create?id=' . $model->id),
                        'id' => 'time-entry-modal-button',
                        'class' => 'btn btn-primary bg-iris border-iris',
                        // disable if creating a new ticket
                        'disabled' => (Yii::$app->controller->action->id == 'create') ? true : false,
                ]); ?>

                <!-- Depending on the current status level, show the reopen button -->
                <?php
                // reopen the ticket. do not show this option on ticket creation screen.
                if (!($model->jobStatus == NULL)){
                        if ($model->jobStatus->level >= 70) {
                                echo Html::a('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-door-open" viewBox="0 0 16 16">
                                                <path d="M8.5 10c-.276 0-.5-.448-.5-1s.224-1 .5-1 .5.448.5 1-.224 1-.5 1"/>
                                                <path d="M10.828.122A.5.5 0 0 1 11 .5V1h.5A1.5 1.5 0 0 1 13 2.5V15h1.5a.5.5 0 0 1 0 1h-13a.5.5 0 0 1 0-1H3V1.5a.5.5 0 0 1 .43-.495l7-1a.5.5 0 0 1 .398.117M11.5 2H11v13h1V2.5a.5.5 0 0 0-.5-.5M4 1.934V15h6V1.077z"/>
                                        </svg> Re-open ticket', Url::to('reopen?id=' . $model->id), [
                                        'class' => 'btn btn-primary bg-lavender border-lavender',
                                        // disable if creating a new ticket
                                        'disabled' => (Yii::$app->controller->action->id == 'create') ? true : false,
                                        'data' => [
                                                        'method' => 'post',
                                                        'confirm' => 'Are you sure you want to re-open this ticket? It will be added back to the workflow and will not be billed until it is resolved and closed again.',
                                                ],
                                ]);
                        }
                }
                ?>

                <!-- Depending on the current status level, show the relevant resolve/closed button -->
                <?php
                // resolve the ticket. do not show this option on ticket creation screen.
                if (!($model->jobStatus == NULL)){
                        if ($model->jobStatus->level < 70 ) {
                                echo Html::a('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-patch-check" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd" d="M10.354 6.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7 8.793l2.646-2.647a.5.5 0 0 1 .708 0"/>
                                                <path d="m10.273 2.513-.921-.944.715-.698.622.637.89-.011a2.89 2.89 0 0 1 2.924 2.924l-.01.89.636.622a2.89 2.89 0 0 1 0 4.134l-.637.622.011.89a2.89 2.89 0 0 1-2.924 2.924l-.89-.01-.622.636a2.89 2.89 0 0 1-4.134 0l-.622-.637-.89.011a2.89 2.89 0 0 1-2.924-2.924l.01-.89-.636-.622a2.89 2.89 0 0 1 0-4.134l.637-.622-.011-.89a2.89 2.89 0 0 1 2.924-2.924l.89.01.622-.636a2.89 2.89 0 0 1 4.134 0l-.715.698a1.89 1.89 0 0 0-2.704 0l-.92.944-1.32-.016a1.89 1.89 0 0 0-1.911 1.912l.016 1.318-.944.921a1.89 1.89 0 0 0 0 2.704l.944.92-.016 1.32a1.89 1.89 0 0 0 1.912 1.911l1.318-.016.921.944a1.89 1.89 0 0 0 2.704 0l.92-.944 1.32.016a1.89 1.89 0 0 0 1.911-1.912l-.016-1.318.944-.921a1.89 1.89 0 0 0 0-2.704l-.944-.92.016-1.32a1.89 1.89 0 0 0-1.912-1.911z"/>
                                        </svg> Resolve ticket', Url::to('resolve?id=' . $model->id), [
                                        'class' => 'btn btn-primary bg-lavender border-lavender',
                                        // disable if creating a new ticket
                                        'disabled' => (Yii::$app->controller->action->id == 'create') ? true : false,
                                        'data' => [
                                                        'method' => 'post',
                                                        'confirm' => 'Are you sure you want to resolve this ticket? It will be closed for modification and submitted to a supervisor for approval.',
                                                ],
                                ]);
                        // close the ticket
                        } elseif ($model->jobStatus->level == 70) {
                                echo Html::a('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-door-closed" viewBox="0 0 16 16">
                                                <path d="M3 2a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v13h1.5a.5.5 0 0 1 0 1h-13a.5.5 0 0 1 0-1H3zm1 13h8V2H4z"/>
                                                <path d="M9 9a1 1 0 1 0 2 0 1 1 0 0 0-2 0"/>
                                        </svg> Close ticket', Url::to('close?id=' . $model->id), [
                                        'class' => 'btn btn-primary bg-lavender border-lavender',
                                        // disable if creating a new ticket
                                        'disabled' => (Yii::$app->controller->action->id == 'create') ? true : false,
                                        'data' => [
                                                        'method' => 'post',
                                                        'confirm' => 'Are you sure you want to close this ticket? It will be eligible for billing, and cannot be re-opened after it is billed.',
                                                ],
                                ]);
                        }
                }
                ?>

                                <!-- save changes -->
                                <?= Html::submitButton('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
                                                                <path d="M11 2H9v3h2z"/>
                                                                <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
                                                        </svg> Save ticket',
                                                        ['class' => 'btn btn-primary bg-pacific-cyan border-pacific-cyan']) ?>
        </div>

        <!-- pill nav -->
        <ul class="nav nav-pills" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pills-general-tab" data-bs-toggle="pill" data-bs-target="#pills-general" type="button" role="tab" aria-controls="pills-general" aria-selected="true">General</button>
                </li>
                <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-technicians-tab" data-bs-toggle="pill" data-bs-target="#pills-technicians" type="button" role="tab" aria-controls="pills-technicians" aria-selected="false">Technicians</button>
                </li>
                <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-equipment-tab" data-bs-toggle="pill" data-bs-target="#pills-equipment" type="button" role="tab" aria-controls="pills-equipment" aria-selected="false">Equipment</button>
                </li>
                <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-time-entries-tab" data-bs-toggle="pill" data-bs-target="#pills-time-entries" type="button" role="tab" aria-controls="pills-time-entries" aria-selected="false">Time entries</button>
                </li>
        </ul>

        <!-- tech note -->
        <div class="row">
                <div class="col">
                        <div class="collapse multi-collapse" id="tech-note">
                                <div class="card card-body">
                                        There will be a text box here soon!
                                        <!-- $form->field($model, 'tech_note')->textarea(['maxlength' => true, 'rows' => 3]) -->
                                </div>
                        </div>
                </div>
        </div>

        <!-- pill content -->
        <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-general" role="tabpanel" aria-labelledby="pills-general-tab">
                        <div class="subsection-info-block">
                                <h2>General details</h2>
                                <p>Details pertaining to the request</p>
                                <!-- this is where the dependent dropdowns are going to be, i think all three can fit in one row-->
                                <div class="question-box">
                                        <div class="row">
                                                <div class="col-md-3">
                                                        <!-- customer type selections -->
                                                        <?= $form->field($model, 'customer_type_id')->radioList($customerTypes); ?>
                                                </div>
                                                <div class="col-md-5">
                                                        <!-- department  selection -->
                                                        <!-- new forms will anticipate CABOCES customer, show departments and department buildings by default -->
                                                        <?= $form->field($model, 'department_id', ['options' => 
                                                                [
                                                                        // if this IS a new ticket OR IS an existing ticket with a CABOCES customer, show department select. else, display none
                                                                        'style' => ($model->customerType == null || $model->customerType->id == 1) ? 'display: block;' : 'display: none;'
                                                                ]
                                                                ])->dropDownList($departments, 
                                                                [
                                                                        'prompt' => 'Select Department',
                                                                        'onchange' => '
                                                                                $.ajax({
                                                                                        type: "POST",
                                                                                        url: "'.Yii::$app->urlManager->createUrl(["ticket/department-building-dependent-dropdown-query"]) . '",
                                                                                        data: {department_search_reference: $(this).val()},
                                                                                        dataType: "json",
                                                                                        success: function(response) {
                                                                                                $("#ticket-division_id").val("");


                                                                                                $("#ticket-department_building_id").empty();
                                                                                                var count = response.length;

                                                                                                if (count === 0) {
                                                                                                        $("#ticket-department_building_id").empty();
                                                                                                        $("#ticket-department_building_id").append("<option value=\'" + id + "\'>Sorry, no buildings available for this department</option>");
                                                                                                } else {
                                                                                                        $("#ticket-department_building_id").append("<option value=\'" + id + "\'>Select Department Building</option>");
                                                                                                        for (var i = 0; i < count; i++) {
                                                                                                                var id = response[i][\'id\'];
                                                                                                                var name = response[i][\'name\'];
                                                                                                                $("#ticket-department_building_id").append("<option value=\'" + id + "\'>" + name + "</option>");

                                                                                                                // this is so redundant...
                                                                                                                var division = response[i][\'division\'];
                                                                                                                $("#ticket-division_id").val(division);
                                                                                                        }
                                                                                                }
                                                                                        }
                                                                                });
                                                                        '
                                                                ]
                                                                
                                                        ); ?>

                                                        <!-- district  selection -->
                                                        <!-- if customer  -->
                                                        <?= $form->field($model, 'district_id', ['options' => 
                                                                [
                                                                        // if this is NOT a new ticket AND is NOT an existing ticket with a CABOCES customer, show district select. else, display none
                                                                        'style' => ($model->customerType != null && $model->customerType->id != 1) ? 'display: block;' : 'display: none;'
                                                                ]
                                                                ])->dropDownList($districts, 
                                                                [
                                                                        'prompt' => 'Select District',
                                                                        'onchange' => '
                                                                                $.ajax({
                                                                                        type: "POST",
                                                                                        url: "'.Yii::$app->urlManager->createUrl(["ticket/district-building-dependent-dropdown-query"]) . '",
                                                                                        data: {district_search_reference: $(this).val()},
                                                                                        dataType: "json",
                                                                                        success: function(response) {
                                                                                                $("#ticket-district_building_id").empty();
                                                                                                var count = response.length;

                                                                                                if (count === 0) {
                                                                                                        $("#ticket-district_building_id").empty();
                                                                                                        $("#ticket-district_building_id").append("<option value=\'" + id + "\'>Sorry, buildings available for this district</option>");
                                                                                                } else {
                                                                                                        $("#ticket-district_building_id").append("<option value=\'" + id + "\'>Select District Building</option>");
                                                                                                        for (var i = 0; i < count; i++) {
                                                                                                                var id = response[i][\'id\'];
                                                                                                                var name = response[i][\'name\'];
                                                                                                                $("#ticket-district_building_id").append("<option value=\'" + id + "\'>" + name + "</option>");
                                                                                                        }
                                                                                                }
                                                                                        }
                                                                                });
                                                                        '
                                                                ]
                                                                
                                                        ); ?>

                                                        <!-- division textbox -->
                                                        <!-- this field is going to be entirely hidden and based off of the department selection. it gets values in a stupidly redundent way in the department ajax success function -->
                                                        <?= $form->field($model, 'division_id')->hiddenInput()->label(false); ?>
                                                </div>
                                                <div class="col-md-4">
                                                        <!-- department buildings -->
                                                        <!-- new forms will anticipate CABOCES customer, show departments and department buildings by default -->
                                                        <?= $form->field($model, 'department_building_id', ['options' => 
                                                                [
                                                                        // if this IS a new ticket OR IS an existing ticket with a CABOCES customer, show department-building select. else, display none
                                                                        'style' => ($model->customerType == null || $model->customerType->id == 1) ? 'display: block;' : 'display: none;'
                                                                ]
                                                        ])->dropDownList(ArrayHelper::map($departmentBuildingData, 'id', 'name'),
                                                                [
                                                                        'prompt' => 'Select Department Building'
                                                                ]
                                                        ); ?>
                                                        <!-- district buildings -->
                                                        <?= $form->field($model, 'district_building_id', ['options' => 
                                                                [
                                                                        // if this iS NOT a new ticket AND is NOT an existing ticket with a CABOCES customer, show district-building select. else, display none
                                                                        'style' => ($model->customerType != null && $model->customerType->id != 1) ? 'display: block;' : 'display: none;'
                                                                ]
                                                        ])->dropDownList(ArrayHelper::map($districtBuildingData, 'id', 'name'),
                                                                [
                                                                        'prompt' => 'Select District Building'
                                                                ]
                                                        ); ?>
                                                </div>
                                        </div>
                                </div>
                                <div class="question-box">
                                        <div class="row">
                                                <div class="col-md-6">
                                                        <?= $form->field($model, 'requester')->textInput(['maxlength' => true]) ?>
                                                </div>
                                                <div class="col-md-6">
                                                        <?= $form->field($model, 'location')->textInput(['maxlength' => true]) ?>
                                                </div>
                                        </div>
                                        <div class="row">
                                                <div class="col-md-6">
                                                        <?= $form->field($model, 'requester_email')->textInput(['maxlength' => true]) ?>
                                                </div>
                                                <div class="col-md-6">
                                                        <?= $form->field($model, 'requester_phone')->textInput(['maxlength' => true]) ?>
                                                </div>
                                        </div>
                                </div>
                                <div class="question-box">
                                        <?= $form->field($model, 'summary')->textInput(['maxlength' => true]) ?>
                                        <?= $form->field($model, 'description')->textarea(['maxlength' => true, 'rows' => 3]) ?>
                                </div>
                                <div class="question-box">
                                        <div class="row">
                                                <div class="col-md-6">
                                                        <?= $form->field($model, 'job_category_id')
                                                                ->dropDownList($categories, ['prompt' => 'Select Category']) ?>
                                                </div>
                                                <div class="col-md-6">
                                                        <?= $form->field($model, 'job_priority_id')
                                                                ->dropDownList($priorities, ['prompt' => 'Select Priority']) ?>
                                                </div>
                                        </div>
                                        <div class="row">
                                                <div class="col-md-6">
                                                        <?php
                                                        /**
                                                         * For statuses: we need to only show the statuses that are selectable when tickets are still OPEN.
                                                         * When tickets are closed/resolved, populate this field with the relevant status and DO NOT allow
                                                         * users to change it. They must use the action buttons at the top instead. (open->resolve->close->reopen)
                                                         */
                                                        if ($model->jobStatus == NULL || $model->jobStatus->level < 70) {
                                                                echo $form->field($model, 'job_status_id')
                                                                        ->dropDownList($statuses, ['prompt' => 'Select Status']);
                                                        } elseif ($model->jobStatus->level >= 70) {
                                                                echo $form->field($model, 'job_status_id')
                                                                        ->dropDownList($nonSelectableStatuses, ["disabled"=>"disabled"]);
                                                        }
                                                        ?>
                                                </div>
                                                <div class="col-md-6">
                                                        <?= $form->field($model, 'job_type_id')
                                                                ->dropDownList($types, ['prompt' => 'Select Type']) ?>
                                                </div>
                                        </div>
                                </div>
                        </div>
                </div>
                <div class="tab-pane fade" id="pills-technicians" role="tabpanel" aria-labelledby="pills-technicians-tab">
                        <div class="subsection-info-block">
                                <h2>Technicians</h2>
                                <p>Technicians assigned to this ticket</p>
                                <div class="alert alert-info p-2" role="alert">
                                        Removing technicians will remove their recorded time entries
                                </div>
                                <div class="question-box">
                                        <!-- all tech assignments -->
                                        <?php
                                        echo $form->field($model, 'users')->widget(Select2::class, [
                                                'data' => $users,
                                                'options' => ['label' => 'Assigned Techs', 'placeholder' => 'Add users ...'],
                                                'pluginOptions' => [
                                                        'allowClear' => true,
                                                        'multiple' => true
                                                ],
                                                'pluginEvents' => [
                                                        'change' => 'function(data) {
                                                                       var data_id = $(this).val();
                                                                       $("input#target").val($(this).val());
                                                        }',
                                                ]
                                        ]);?>
                                        <!-- primary tech assignment -->
                                        <?= $form->field($model, 'primary_tech_id')
                                        ->dropDownList(ArrayHelper::map($assignedTechData, 'user_id', 'username'),
                                                [
                                                        'prompt' => 'Select Primary Tech'
                                                ]
                                        );?>
                                </div>
                        </div>
                </div>
                <div class="tab-pane fade" id="pills-equipment" role="tabpanel" aria-labelledby="pills-equipment-tab">
                        <div class="subsection-info-block">
                                <h2>Equipment</h2>
                                <p>Equipment associated with this ticket</p>
                        </div>
                </div>
                <div class="tab-pane fade" id="pills-time-entries" role="tabpanel" aria-labelledby="pills-time-entries-tab">
                        <div class="subsection-info-block">
                                <h2>Time entries</h2>
                                <p>Hours spent on the current ticket</p>
                                <div id="ticket-time-stats" class="d-flex flex-wrap justify-content-evenly | mb-2">
                                        <div class="stat-box flex-fill">
                                                <p>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="1rem" height="1rem" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                                                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                                                        </svg>
                                                        Total Tech Time
                                                </p>
                                                <p id="total-ticket-tech-time" class="totaled-hours"><?= TimeEntry::getTotalTicketTimeFor($model->id, 'tech_time') ?> (hrs)</p>
                                        </div>
                                        <div class="stat-box flex-fill">
                                                <p>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="1rem" height="1rem" fill="currentColor" class="bi bi-alarm-fill" viewBox="0 0 16 16">
                                                                <path d="M6 .5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1H9v1.07a7.001 7.001 0 0 1 3.274 12.474l.601.602a.5.5 0 0 1-.707.708l-.746-.746A6.97 6.97 0 0 1 8 16a6.97 6.97 0 0 1-3.422-.892l-.746.746a.5.5 0 0 1-.707-.708l.602-.602A7.001 7.001 0 0 1 7 2.07V1h-.5A.5.5 0 0 1 6 .5m2.5 5a.5.5 0 0 0-1 0v3.362l-1.429 2.38a.5.5 0 1 0 .858.515l1.5-2.5A.5.5 0 0 0 8.5 9zM.86 5.387A2.5 2.5 0 1 1 4.387 1.86 8.04 8.04 0 0 0 .86 5.387M11.613 1.86a2.5 2.5 0 1 1 3.527 3.527 8.04 8.04 0 0 0-3.527-3.527"/>
                                                        </svg>
                                                        Total Overtime
                                                </p>
                                                <p id="total-ticket-overtime" class="totaled-hours"><?= TimeEntry::getTotalTicketTimeFor($model->id, 'overtime') ?> (hrs)</p>
                                        </div>
                                        <div class="stat-box flex-fill">
                                                <p>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="1rem" height="1rem" fill="currentColor" class="bi bi-car-front-fill" viewBox="0 0 16 16">
                                                                <path d="M2.52 3.515A2.5 2.5 0 0 1 4.82 2h6.362c1 0 1.904.596 2.298 1.515l.792 1.848c.075.175.21.319.38.404.5.25.855.715.965 1.262l.335 1.679q.05.242.049.49v.413c0 .814-.39 1.543-1 1.997V13.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-1.338c-1.292.048-2.745.088-4 .088s-2.708-.04-4-.088V13.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-1.892c-.61-.454-1-1.183-1-1.997v-.413a2.5 2.5 0 0 1 .049-.49l.335-1.68c.11-.546.465-1.012.964-1.261a.8.8 0 0 0 .381-.404l.792-1.848ZM3 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2m10 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2M6 8a1 1 0 0 0 0 2h4a1 1 0 1 0 0-2zM2.906 5.189a.51.51 0 0 0 .497.731c.91-.073 3.35-.17 4.597-.17s3.688.097 4.597.17a.51.51 0 0 0 .497-.731l-.956-1.913A.5.5 0 0 0 11.691 3H4.309a.5.5 0 0 0-.447.276L2.906 5.19Z"/>
                                                        </svg>
                                                        Total Travel Time
                                                </p>
                                                <p id="total-ticket-travel-time" class="totaled-hours"><?= TimeEntry::getTotalTicketTimeFor($model->id, 'travel_time') ?> (hrs)</p>
                                        </div>
                                        <div class="stat-box flex-fill">
                                                <p>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="1rem" height="1rem" fill="currentColor" class="bi bi-luggage-fill" viewBox="0 0 16 16">
                                                                <path d="M2 1.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V5h.5A1.5 1.5 0 0 1 8 6.5V7H7v-.5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 .5.5H4v1H2.5v.25a.75.75 0 0 1-1.5 0v-.335A1.5 1.5 0 0 1 0 13.5v-7A1.5 1.5 0 0 1 1.5 5H2zM3 5h2V2H3z"/>
                                                                <path d="M2.5 7a.5.5 0 0 1 .5.5v5a.5.5 0 0 1-1 0v-5a.5.5 0 0 1 .5-.5m10 1v-.5A1.5 1.5 0 0 0 11 6h-1a1.5 1.5 0 0 0-1.5 1.5V8H8v8h5V8zM10 7h1a.5.5 0 0 1 .5.5V8h-2v-.5A.5.5 0 0 1 10 7M5 9.5A1.5 1.5 0 0 1 6.5 8H7v8h-.5A1.5 1.5 0 0 1 5 14.5zm9 6.5V8h.5A1.5 1.5 0 0 1 16 9.5v5a1.5 1.5 0 0 1-1.5 1.5z"/>
                                                        </svg>
                                                        Total Itinerate Time
                                                </p>
                                                <p id="total-ticket-itinerate-time" class="totaled-hours"><?= TimeEntry::getTotalTicketTimeFor($model->id, 'itinerate_time') ?> (hrs)</p>
                                        </div>
                                        <div class="total-billable-hours-box stat-box flex-fill | fw-bold">
                                                <p>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="1rem" height="1rem" fill="currentColor" class="bi bi-clock-fill" viewBox="0 0 16 16">
                                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z"/>
                                                </svg>
                                                        Total Billable Hours
                                                </p>
                                                <p id="total-ticket-hours" class="totaled-hours"><?= TimeEntry::getTotalTicketTimeFor($model->id, 'all') ?> (hrs)</p>
                                        </div>
                                </div>
                                <div class="table-container container-fluid overflow-x-scroll">
                                <?php Pjax::begin(['id' => 'tech-time-entries']); ?>
                                        <?= GridView::widget([
                                                'dataProvider' => $techTimeEntryDataProvider,
                                                'filterModel' => $techTimeEntrySearch,
                                                // 'showFooter' => true,
                                                'tableOptions' => ['class' => 'table table-bordered'],
                                                'summary' => '',
                                                'columns' => [
                                                        [
                                                                'class' => ActionColumn::class,
                                                                'template' => '{delete}',
                                                                'urlCreator' => function ($action, TimeEntry $model, $key, $index) {
                                                                    return Url::to(['time-entry/' . $action, 'id' => $model->id]);
                                                                }
                                                        ],
                                                        'entry_date',
                                                        'tech' => [
                                                                'attribute' => 'user_name',
                                                                'value' => function($model) {
                                                                        return '<a href="/user/view?id=' . ($model->user_id != null ? $model->user_id : '') . '">' . ($model->user_id != null ? $model->user->username : '') . '</a>';
                                                                },
                                                                'format' => 'raw',
                                                                'label' => 'Tech',
                                                        ],
                                                        'tech_time' => [
                                                                'attribute' => 'tech_time',
                                                                'value' => function ($model) {
                                                                        return ($model->tech_time == 0 ? '' : $model->tech_time);
                                                                }
                                                        ],
                                                        'overtime' => [
                                                                'attribute' => 'overtime',
                                                                'value' => function ($model) {
                                                                        return ($model->overtime == 0 ? '' : $model->overtime);
                                                                }
                                                        ],
                                                        'travel_time' => [
                                                                'attribute' => 'travel_time',
                                                                'value' => function ($model) {
                                                                        return ($model->travel_time == 0 ? '' : $model->travel_time);
                                                                }
                                                        ],
                                                        'itinerate_time' => [
                                                                'attribute' => 'itinerate_time',
                                                                'value' => function ($model) {
                                                                        return ($model->itinerate_time == 0 ? '' : $model->itinerate_time);
                                                                }
                                                        ],
                                                        [
                                                                'attribute' => 'last_modified_by',
                                                                'value' => function($model) {
                                                                        return '<a href="/user/view?id=' . ($model->last_modified_by_user_id != null ? $model->last_modified_by_user_id : '') . '">' . ($model->last_modified_by_user_id != null ? $model->lastModifiedBy->username : '') . '</a>';
                                                                },
                                                                'format' => 'raw',
                                                        ],
                                                ],
                                        ]); ?>
                                <?php Pjax::end(); ?>
                                </div>
                        </div>
                </div>
        </div>

        <!-- action buttons -->
        <div class='secondary-action-button-bar'>
                <?= Html::a('Back', Yii::$app->request->referrer, ['class' => 'btn btn-secondary']); ?>
                <?= Html::button('Technicians', ['id' => 'secondary-pill-nav-technicians', 'class' => 'btn btn-outline-secondary']); ?>
                <?= Html::button('Equipment', ['id' => 'secondary-pill-nav-equipment', 'class' => 'btn btn-outline-secondary']); ?>
                <?= Html::button('Time entries', ['id' => 'secondary-pill-nav-time-entries', 'class' => 'btn btn-outline-secondary']); ?>
                <?= Html::submitButton('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
                                                <path d="M11 2H9v3h2z"/>
                                                <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
                                        </svg> Save ticket', ['class' => 'btn btn-primary bg-pacific-cyan border-pacific-cyan']); ?>
        </div>
        <?php ActiveForm::end(); ?>

</div>