<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

$this->title = 'Loan Inventory';
?>
<div> <!-- Main container -->
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'new_prop_tag')->dropDownList($newPropTags); ?>
    <div class="form-group">
        <?= Html::submitButton('Return', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>