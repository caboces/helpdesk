<?php

use yii\helpers\Html;
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
                <?= Html::button('Add tech note', [
                        'class' => 'btn btn-secondary',
                        'data-bs-toggle' => 'collapse',
                        'data-bs-target' => '#tech-note',
                        'aria-expanded' => 'false',
                        'aria-controls' => '#tech-note'
                ]); ?>
                <?= Html::submitButton('Save', ['class' => 'btn btn-primary bg-pacific-cyan border-pacific-cyan']) ?>
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
                                                <div class="col-md-4">
                                                        <?= $form->field($model, 'customer_type_id')
                                                                        ->dropDownList($customerTypes, [
                                                                                'prompt' => 'Select a customer type...',
                                                                                // this is the ajax that allows us to post changes for the dependent dropdowns
                                                                                'onchange' => '
                                                                                        $.ajax({
                                                                                                type: "POST",
                                                                                                url: "' . Yii::$app->urlManager->createUrl(["site/dependent-dropdown-query"]) . '",
                                                                                                data: {customer_search_reference}: $(this).val()},
                                                                                                dataType: "json",
                                                                                                success: function(response) {
                                                                                                        // enable the ddl
                                                                                                        $("#district-name").prop("disabled", false);
                                                                                                        // clear previous ddl data
                                                                                                        $("#district-name").empty();
                                                                                                        var count = response.length;

                                                                                                        // if no options are available, say so. else, populate the ddl appropriately
                                                                                                        if(count === 0) {
                                                                                                                $("#district-name").empty();
                                                                                                                $("#district-name").prop("disabled", "disabled");
                                                                                                                $("#district-name").append("<option value=\'" + id + "\'>Sorry, there are no options available for this selection</option>");
                                                                                                        } else {
                                                                                                                $("#district-name").append("<option value=\'" + id + "\'>Select a district...</option>");
                                                                                                                for(var i = 0; i<count; i++){
                                                                                                                    var id = response[i][\'id\'];
                                                                                                                    var name = response[i][\'name\'];
                                                                                                                    $("#district-name").append("<option value=\'" + id + "\'>" + name + "</option>");
                                                                                                        }
                                                                                                }
                                                                                        });
                                                                                '
                                                                        ]
                                                                );
                                                        ?>
                                                </div>
                                                <div class="col-md-4">
                                                        <!-- district or division -->
                                                        <?= $form->field($model, 'district_id')
                                                                        // DDL expects to recieve an associative array in the form of "id" and "name"
                                                                        ->dropDownList(ArrayHelper::map([], 'id', 'name'), ['prompt' => 'Select District']) ?>
                                                </div>
                                                <div class="col-md-4">
                                                        <!-- department or building -->
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
                                <div class="question-box">
                                        <?php
                                        echo $form->field($model, 'users')->widget(Select2::classname(), [
                                                'data' => $users,
                                                'options' => ['placeholder' => 'Add users ...'],
                                                'pluginOptions' => [
                                                        'allowClear' => true,
                                                        'multiple' => true
                                                ],
                                        ]);
                                        ?>
                                </div>
                        </div>
                </div>
                <div class="tab-pane fade" id="pills-time-entries" role="tabpanel" aria-labelledby="pills-time-entries-tab">
                        <div class="subsection-info-block">
                                <h2>Time entries</h2>
                                <p>Hours spent on current ticket</p>
                        </div>
                </div>
        </div>
        <!-- action buttons -->
        <div class='secondary-action-button-bar'>
                <?= Html::a('Back', Yii::$app->request->referrer, ['class' => 'btn btn-secondary']); ?>
                <?= Html::button('Assign tech', ['class' => 'btn btn-outline-secondary']); ?>
                <?= Html::button('Add time entry', ['class' => 'btn btn-outline-secondary']); ?>
                <?= Html::submitButton('Save', ['class' => 'btn btn-primary bg-pacific-cyan border-pacific-cyan']); ?>
        </div>
        <?php ActiveForm::end(); ?>

</div>