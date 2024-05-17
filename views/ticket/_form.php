<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\models\Ticket;
use app\models\TimeEntry;
use yii\grid\GridView;
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

        <?php $form = ActiveForm::begin(); ?>

        <!-- action buttons -->
        <div class='container-fluid p-2 | bg-dark shadow-sm'>
                <?= Html::a('Back', Yii::$app->request->referrer, ['class' => 'btn btn-secondary']); ?>
                <?= Html::button('New tech note', [
                        'class' => 'btn btn-primary bg-iris border-iris',
                        'data-bs-toggle' => 'collapse',
                        'data-bs-target' => '#tech-note',
                        'aria-expanded' => 'false',
                        'aria-controls' => '#tech-note'
                ]); ?>
                <?= Html::button('New time entry', ['value' => Url::to('/time-entry/create'), 'id' => 'time-entry-modal-button', 'class' => 'btn btn-primary bg-iris border-iris']) ?>
                <?= Html::submitButton('Save ticket', ['class' => 'btn btn-primary bg-pacific-cyan border-pacific-cyan']) ?>
        </div>

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
                                                        <?= $form->field($model, 'job_status_id')
                                                                ->dropDownList($statuses, ['prompt' => 'Select Status']) ?>
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
                                <div class="alert alert-warning" role="alert">
                                        Removing technicians will remove their recorded time entries!
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
                                <p>Hours spent on current ticket</p>
                                <div class="table-container container-fluid overflow-x-scroll">
                                        <?= GridView::widget([
                                                'dataProvider' => $techTimeEntryDataProvider,
                                                'tableOptions' => ['class' => 'table table-bordered'],
                                                'columns' => [
                                                        'entry_date',
                                                        'username',
                                                        'tech_time',
                                                        'overtime',
                                                        'travel_time',
                                                        'itinerate_time',
                                                ],
                                        ]); ?>
                                </div>
                        </div>
                </div>
        </div>
        <!-- action buttons -->
        <div class='secondary-action-button-bar'>
                <?= Html::a('Back', Yii::$app->request->referrer, ['class' => 'btn btn-secondary']); ?>
                <?= Html::button('Technicians', ['class' => 'btn btn-outline-secondary']); ?>
                <?= Html::button('Equipment', ['class' => 'btn btn-outline-secondary']); ?>
                <?= Html::button('Time entries', ['class' => 'btn btn-outline-secondary']); ?>
                <?= Html::submitButton('Save ticket', ['class' => 'btn btn-primary bg-pacific-cyan border-pacific-cyan']); ?>
        </div>
        <?php ActiveForm::end(); ?>

</div>