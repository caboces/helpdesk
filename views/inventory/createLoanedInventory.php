<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

$this->title = 'Loan Inventory';
?>
<div> <!-- Main container -->
    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'new_prop_tag')->dropDownList($newPropTags); ?>
    <?= $form->field($model, 'borrower_name')->textInput(['maxlength' => true, 'max' => 45]); ?>
    <?= $form->field($model, 'borrower_email')->input('email', ['email', 'maxlength' => true, 'max' => 45]); ?>
    <?= $form->field($model, 'borrower_phone')->input('phone', ['phone', 'maxlength' => true, 'max' => 45]); ?>
    <?= $form->field($model, 'borrower_loc')->input(['maxlength' => true, 'max' => 45]); ?>
    <?= $form->field($model, 'borrower_reason')->textarea(['maxlength' => true, 'max' => 200]); ?>
    <?= $form->field($model, 'date_borrowed')->input('date', ['date', 'value' => date('Y-m-d')]); ?>
    <div class="form-group">
        <?= Html::submitButton('Loan Inventory', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>