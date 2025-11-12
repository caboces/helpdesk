<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\BlockedIpAddress $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="blocked-ip-address-form">
    <p>Fill out the form below to block a certain IP address.</p>
    <p>You can block IPv4 or IPv6 connections. IPv4 IP addresses have 4 numbers 0-255 separated by periods, e.g., 76.43.123.9.</p>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ip_address')->textInput(['maxlength' => true])->label('IP Address') ?>

    <?= $form->field($model, 'reason')->textarea(['maxlength' => true])->label('Reason') ?>

    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
