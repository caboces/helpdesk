<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\TicketNote $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="ticket-note-form">

    <?php $form = ActiveForm::begin([
        'id' => 'add-ticket-note-form',
        'enableClientValidation' => true,
        'validateOnSubmit' => true,
    ]); ?>

    <!-- Hidden user id field -->
    <?= $form->field($model, "last_modified_by_user_id", [
            'template' => '{input}',
            'options' => ['tag' => false],
            'inputOptions' => ['value' => Yii::$app->user->id]
        ])->hiddenInput([
            'readonly' => true, 
        ])->label(false)
    ?>

    <?php
        if (isset($ticket_id)) {
            echo $form->field($model, "ticket_id", ['inputOptions' => ['value' => $ticket_id]])->textInput(['readonly' => true, 'class' => 'read-only form-control']);
        } else {
            echo $form->field($model, "ticket_id")->textInput();
        }
    ?>

    <?= $form->field($model, 'note')->textarea(['maxlength' => true]) ?>


    <div class="form-group">
        <?= Html::submitButton('Confirm new tech journal entry', [
            'id' => 'confirm-ticket-note',
            'class' => 'mt-4 btn btn-primary bg-pacific-cyan border-pacific-cyan',
            'form' => 'add-ticket-note-form',
        ]); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
