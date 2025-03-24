<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Return Loaned Inventory';
?>
<div>
    <div class="title-icon d-flex align-items-center">
        <i class="fa-solid fa-hand-holding-hand fa-2xl"></i>
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <p>Fill out the form below to mark a certain CABOCES inventory item as returned.</p>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'new_prop_tag')->dropDownList($newPropTags); ?>
    <div class="form-group">
        <?= Html::submitButton('Return Loaned Inventory', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>