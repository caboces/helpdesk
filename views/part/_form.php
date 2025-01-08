<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Part $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="part-form">

    <?php $form = ActiveForm::begin([
        'id' => 'add-part-form',
        'enableClientValidation' => true,
        'validateOnSubmit' => true,
    ]); ?>
    <div id="parts-box" class="expanding-input-section asset-container">
        <h3>Add Parts</h3>
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
                    <?php
                        if ($ticket_id) {
                            echo $form->field($model, 'ticket_id', ['inputOptions' => ['value' => $ticket_id]])->textInput(['readonly' => true, 'class' => 'read-only form-control']);
                        } else {
                            echo $form->field($model, 'ticket_id')->textInput();
                        }
                    ?>
                </div>
                <div class="col">
                    <?= $form->field($model, 'part_number')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <?= $form->field($model, 'part_name')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <?= $form->field($model, 'quantity')->textInput() ?>
                </div>
                <div class="col">
                    <?= $form->field($model, 'unit_price')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <?= $form->field($model, 'pending_delivery')->radioList(['0' => 'Yes', '1' => 'No']) ?>
                </div>
            </div>
            <div class="row">
                <?= $form->field($model, 'note')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="form-group">
                <?= Html::button('Remove', ['class' => 'modal-button-remove btn btn-outline-secondary border-imperial-red imperial-red btn-skinny']); ?>
                <?= Html::button('Add', ['class' => 'modal-button-duplicate btn btn-primary bg-iris border-iris btn-skinny']); ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
