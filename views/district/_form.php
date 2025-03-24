<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\District $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="district-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="row">
        <?= $form->field($model, 'description')->textarea(['maxlength' => true]) ?>
    </div>

    <div class="row">
        <?= $form->field($model, 'component_district')->dropDownList([
            '1' => 'Yes',
            '0' => 'No',
        ])->label('Is Component District?') ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Submit District', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
