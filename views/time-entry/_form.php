<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\TimeEntry $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="time-entry-form">

    <?php $form = ActiveForm::begin(); ?>

    <p>Add new hours to this ticket</p>

    <?= $form->field($model, 'tech_time')->textInput() ?>

    <?= $form->field($model, 'overtime')->textInput() ?>

    <?= $form->field($model, 'travel_time')->textInput() ?>

    <?= $form->field($model, 'itinerate_time')->textInput() ?>

    <?= $form->field($model, 'entry_date')->date() ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'ticket_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Confirm new time entry', ['class' => 'btn btn-primary bg-pacific-cyan border-pacific-cyan']); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
