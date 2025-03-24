<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

$this->title = 'Create Loaned Inventory';
?>
<div>
    <div class="title-icon d-flex align-items-center">
        <i class="fa-solid fa-handshake fa-2xl"></i>
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <p>Fill out the form below to mark a certain CABOCES inventory item as borrowed.</p>
    <?php $form = ActiveForm::begin(); ?>
    
    <div class="row">
        <?= $form->field($model, 'new_prop_tag')->dropDownList($newPropTags)->label("Asset Tag") ?>
    </div>
    <div class="row">
        <div class="col">
            <?= $form->field($model, 'borrower_name')->textInput(['maxlength' => true, 'max' => 45])->label("Borrower's Name") ?>
        </div>
        <div class="col">
            <?= $form->field($model, 'borrower_email')->input('email', ['email', 'maxlength' => true, 'max' => 45])->label("Borrower's Email") ?>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <?= $form->field($model, 'borrower_phone')->input('phone', ['phone', 'maxlength' => true, 'max' => 45])->label("Borrower's Phone") ?>
        </div>
        <div class="col">
            <?= $form->field($model, 'borrower_phone')->input('phone', ['phone', 'maxlength' => true, 'max' => 45])->label("Borrower's Phone") ?>
        </div>
    </div>
    <div class="row">
        <?= $form->field($model, 'borrower_reason')->textarea(['maxlength' => true, 'max' => 200])->label('Reason') ?>
    </div>
    <div class="row">
        <?= $form->field($model, 'date_borrowed')->input('date', ['date', 'value' => date('Y-m-d')])->label('Date Borrowed') ?>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Loan Inventory Out', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>