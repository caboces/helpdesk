<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Asset $model */
/** @var yii\bootstrap5\ActiveForm $form */

?>

<div class="asset-form">

    <?php $form = ActiveForm::begin([
        'id' => 'add-asset-form',
        'enableClientValidation' => true,
        'validateOnSubmit' => true,
    ]); ?>

    <!-- Hidden ticket id field if it is filled -->
    <div id="asset-box" class="dynamic-form asset-container">
        
        <?php foreach ($models as $index => $model): ?>

        <div class="dynamic-form-input-group question-box-no-trim">
            <?= $form->field($model, "[$index]last_modified_by_user_id", [
                'template' => '{input}',
                'options' => ['tag' => false],
                'inputOptions' => ['value' => Yii::$app->user->id]
            ])->hiddenInput([
                'readonly' => true, 
                'class' => 'dynamic-form-clone-value',
            ])->label(false)
            ?>
            <div class="row">
                <div class="col">
                    <?= $form->field($model, "[$index]new_prop_tag")->textInput() ?>
                </div>
                <div class="col">
                    <?= $form->field($model, "[$index]po_number")->textInput() ?>
                </div>
                <div class="col">
                    <?php if ($ticket_id): ?>
                        <?= $form->field($model, "[$index]ticket_id", ['inputOptions' => ['value' => $ticket_id]])->textInput(['readonly' => true, 'class' => 'read-only form-control dynamic-form-clone-value']); ?>
                    <?php else: ?>
                        <?= $form->field($model, "[$index]ticket_id")->textInput(); ?>
                    <?php endif; ?>
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
        <!-- Disable submitting the asset if we don't know the ticket id -->
        <?= Html::submitButton('Create assets', [
            'id' => 'create-assets',
            'class' => 'mt-4 btn btn-primary bg-pacific-cyan border-pacific-cyan',
            'form' => 'add-asset-form',
        ]); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
