<?php

use yii\bootstrap5\ActiveForm;

?>
<div> <!-- Main container -->
    <?php $form = ActiveForm::begin() ?>
    <!-- Focus on formatting later 
        Required fields:
        - asset tag
        - borrower name
        - borrower email (not required)
        - borrower phone
        - borrower location
        - borrower reason
        - date borrowed
    -->
    <?= $form->field($model, 'new_prop_tag')->dropDownList($newPropTags); ?>
    <?= $form->field($model, 'borrower_name')->textInput(['maxlength' => true]); ?>
    <?= $form->field($model, 'borrower_email')->input('email', ['maxlength' => true]); ?>
    <?= $form->field($model, 'borrower_phone')->input('phone', ['maxlength' => true]); ?>
    <?= $form->field($model, 'borrower_location')->textarea(['maxlength' => true]); ?>
    <?= $form->field($model, 'borrower_reason')->textarea(['maxlength' => true]); ?>
    <?= $form->field($model, 'date_borrowed')->input('date', ['value' => date('Y-m-d')]); ?>

    <?php ActiveForm::end(); ?>
</div>