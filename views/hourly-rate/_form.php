<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\HourlyRate $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="hourly-rate-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <?= $form->field($model, 'job_type_id')->dropDownList($jobTypesOptions)->label('Job Type') ?>
    </div>
    <div class="row">
        <div class="col">
        <small>Please enter a valid currency rate between 0 to 99.99.</small>
            <?= $form->field($model, 'rate')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col">
            <small>(Optional) Please enter a valid currency rate between 0 to 99.99.</small>
            <?= $form->field($model, 'summer_rate')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <?= $form->field($model, 'description')->textarea(['maxlength' => true]) ?>

    <?= $form->field($model, "first_day_effective")->input('date', [
        'dateFormat' => 'php:m/d/Y',
    ]); ?>

    <?= $form->field($model, "last_day_effective")->input('date', [
        'dateFormat' => 'php:m/d/Y',
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
