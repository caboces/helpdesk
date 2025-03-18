<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\HourlyRate $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="hourly-rate-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'job_type_id')->textInput() ?>

    <?= $form->field($model, 'rate')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'summer_rate')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'first_day_effective')->textInput() ?>

    <?= $form->field($model, 'last_day_effective')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
