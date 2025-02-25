<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Part $model */
/** @var yii\bootstrap5\ActiveForm $form */
?>

<div class="part-form">

    <?php $form = ActiveForm::begin([
        'id' => 'add-part-form',
        'enableClientValidation' => true,
        'validateOnSubmit' => true,
    ]); ?>
    <div id="parts-box" class="dynamic-form asset-container">
        <h3>Add Parts</h3>

        <?php foreach ($models as $index => $model): ?>

        <div class="dynamic-form-input-group question-box-no-trim">
            <?= $form->field($model, "[$index]last_modified_by_user_id", [
                'template' => '{input}',
                'options' => ['tag' => false],
                'inputOptions' => ['value' => Yii::$app->user->id]
            ])->hiddenInput([
                'class' => 'dynamic-form-clone-value',
                'readonly' => true, 
            ])->label(false)
            ?>
            <div class="row">
                <div class="col">
                    <?php
                        if ($ticket_id) {
                            echo $form->field($model, "[$index]ticket_id", ['inputOptions' => ['value' => $ticket_id]])->textInput(['readonly' => true, 'class' => 'read-only form-control dynamic-form-clone-value']);
                        } else {
                            echo $form->field($model, "[$index]ticket_id")->textInput();
                        }
                    ?>
                </div>
                <div class="col">
                    <?= $form->field($model, "[$index]part_number")->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <?= $form->field($model, "[$index]part_name")->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <?= $form->field($model, "[$index]quantity")->textInput() ?>
                </div>
                <div class="col">
                    <?= $form->field($model, "[$index]unit_price")->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <?= $form->field($model, "[$index]pending_delivery")->radioList(['0' => 'Yes', '1' => 'No']) ?>
                </div>
                <div class="col">
                    <?= $form->field($model, "[$index]po_number")->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="form-group">
                <?= Html::button('Remove', ['class' => 'dynamic-form-button-remove btn btn-outline-secondary border-imperial-red imperial-red btn-skinny']); ?>
                <?= Html::button('Add', ['class' => 'dynamic-form-button-add btn btn-primary bg-iris border-iris btn-skinny']); ?>
            </div>
        </div>

        <?php endforeach; ?>

    </div>

    <div class="form-group">
        <?= Html::submitButton('Create parts', [
            'id' => 'create-parts',
            'class' => 'mt-4 btn btn-primary bg-pacific-cyan border-pacific-cyan',
            'form' => 'add-part-form',
        ]); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
