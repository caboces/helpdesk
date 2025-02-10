<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\TicketDraft $model */
/** @var yii\bootstrap5\ActiveForm $form */
?>
<script>
    function reCaptchaOnSubmit(token) {
        $('#ticket-draft-form').submit()
    }
</script>
<div class="ticket-draft-form">
    <!-- TODO Move all onchange inline'd javascript code to a javascript file at some point -->
    <?php $form = ActiveForm::begin([
        'id' => 'ticket-draft-form',
        'enableClientValidation' => true,
        'validateOnSubmit' => true,
    ]); ?>
    <div>
        <div class="question-box">
            <div class="row">
                <div class="col-md-3">
                    <!-- customer type selections -->
                    <?= $form->field($model, 'customer_type_id')->radioList($customerTypes); ?>
                </div>
                <div class="col-md-5">
                    <!-- department  selection -->
                    <!-- new forms will anticipate CABOCES customer, show departments and department buildings by default -->
                    <?= $form->field($model, 'department_id', ['options' => [
                            // if this IS a new ticket OR IS an existing ticket with a CABOCES customer, show department select. else, display none
                            'style' => ($model->customerType == null || $model->customerType->id == 1) ? 'display: block;' : 'display: none;'
                    ]])->dropDownList($departments, [
                        'prompt' => 'Select Department',
                    ]); ?>
                    <!-- district selection -->
                    <!-- if customer is district -->
                    <?= $form->field($model, 'district_id', ['options' => [
                        // if this is NOT a new ticket AND is NOT an existing ticket with a CABOCES customer, show district select. else, display none
                        'style' => ($model->customerType != null && $model->customerType->id != 1) ? 'display: block;' : 'display: none;'
                    ]])->dropDownList($districts, [
                        'prompt' => 'Select District',
                    ]); ?>
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
                    <?= $form->field($model, 'requestor')->textInput(['maxlength' => 100, 'placeholder' => 'First Last', 'required' => true]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'location')->textInput(['maxlength' => 100, 'required' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'email')->textInput(['maxlength' => 100, 'required' => true]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'phone')->textInput(['maxlength' => 100, 'placeholder' => '(xxx) xxx-xxxx', 'required' => true]) ?>
                </div>
            </div>
        </div>
        <div class="question-box">
            <div class="row">
                <?= $form->field($model, 'summary')->textInput(['maxlength' => 100, 'required' => true]) ?>
                <small class="fst-italic">100 character limit</small>
            </div>
            <div class="row">
                <?= $form->field($model, 'description')->textarea(['maxlength' => 500, 'required' => true]) ?>
                <small class="fst-italic">500 character limit</small>
            </div>
        </div>

        <!-- Hidden fields -->
        <?= Html::input('text', 'vc090h3n', null, ['hidden' => true]) ?>
        
        <!-- division textbox -->
        <?= $form->field($model, 'division_id')->hiddenInput()->label(false); ?>

        <div class="form-group">
            <p>Please note that we will not be accepting file uploads in this form due to security limitations.</p>
            <?= Html::submitButton('Submit Ticket to CABOCES', [
                'class' => 'btn btn-success g-recaptcha', 
                'data-sitekey' => '6LcEMsEqAAAAACHqBOkDNZDP7CFW2JjMLvPdN7IQ',
                'data-callback' => 'reCaptchaOnSubmit',
                'data-action' => 'submit',
                ]) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
