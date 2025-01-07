<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\TicketEquipment $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="ticket-equipment-form">

    <?php $form = ActiveForm::begin([
        'id' => 'add-ticket-equipment-form',
        'enableClientValidation' => true,
        'validateOnSubmit' => true,
    ]); ?>

    <div id="ticket-equipment-box" class="ticket-equipment-container">
        <div class="question-box-no-trim">
            <?= $form->field($model, 'last_modified_by_user_id', [
                        'template' => '{input}',
                        'options' => ['tag' => false],
                        'inputOptions' => ['value' => Yii::$app->user->id]
                    ])->hiddenInput([
                        'readonly' => true, 
                    ])->label(false)
            ?>
            <div class="row">
                <div class="col">
                    <?= $form->field($model, 'new_prop_tag')->textInput() ?>
                </div>
                <div class="col">
                    <?= $form->field($model, 'ticket_id', ['inputOptions' => ['value' => $ticket->id]])->textInput(['readonly' => true, 'class' => 'read-only form-control']) ?>
                </div>
            </div>
            <div class="form-group">
                <?= Html::button('Remove', ['class' => 'ticket-equip-modal-button-remove btn btn-outline-secondary border-imperial-red imperial-red btn-skinny']); ?>
                <?= Html::button('Duplicate', ['class' => 'ticket-equip-modal-button-duplicate btn btn-primary bg-iris border-iris btn-skinny']); ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Confirm ticket equipment', [
            'id' => 'confirm-ticket-equipment',
            'class' => 'mt-4 btn btn-primary bg-pacific-cyan border-pacific-cyan',
            'form' => 'add-ticket-equipment-form',
        ]); ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php $form = ActiveForm::begin([
        'id' => 'add-part-form',
        'enableClientValidation' => true,
        'validateOnSubmit' => true,
    ]); ?>

    <div id="part-box" class="part-container">
        <div class="question-box-no-trim">
            <?= $form->field($model, 'last_modified_by_user_id', [
                        'template' => '{input}',
                        'options' => ['tag' => false],
                        'inputOptions' => ['value' => Yii::$app->user->id]
                    ])->hiddenInput([
                        'readonly' => true, 
                    ])->label(false)
            ?>
            <div class="row">
                <div class="col">
                    <?= $form->field($model, 'new_prop_tag')->textInput() ?>
                </div>
                <div class="col">
                    <?= $form->field($model, 'ticket_id', ['inputOptions' => ['value' => $ticket->id]])->textInput(['readonly' => true, 'class' => 'read-only form-control']) ?>
                </div>
            </div>
            <div class="form-group">
                <?= Html::button('Remove', ['class' => 'ticket-equip-modal-button-remove btn btn-outline-secondary border-imperial-red imperial-red btn-skinny']); ?>
                <?= Html::button('Duplicate', ['class' => 'ticket-equip-modal-button-duplicate btn btn-primary bg-iris border-iris btn-skinny']); ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Confirm ticket equipment', [
            'id' => 'confirm-ticket-equipment',
            'class' => 'mt-4 btn btn-primary bg-pacific-cyan border-pacific-cyan',
            'form' => 'add-ticket-equipment-form',
        ]); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
