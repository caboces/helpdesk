<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Asset $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="asset-form">

    <?php $form = ActiveForm::begin([
        'id' => 'add-asset-form',
        'enableClientValidation' => true,
        'validateOnSubmit' => true,
    ]); ?>

    <div id="asset-box" class="expanding-input-section asset-container">
        <h3>Add Asset</h3>
        
        <?php foreach ($models as $index => $model): ?>

        <div class="duplicate-input-group question-box-no-trim">
            <?= $form->field($model, "[$index]last_modified_by_user_id", [
                        'template' => '{input}',
                        'options' => ['tag' => false],
                        'inputOptions' => ['value' => Yii::$app->user->id]
                    ])->hiddenInput([
                        'readonly' => true, 
                    ])->label(false)
            ?>
            <div class="row">
                <div class="col">
                    <?= $form->field($model, "[$index]new_prop_tag")->textInput() ?>
                </div>
                <div class="col">
                    <?php
                        if ($ticket_id) {
                            echo $form->field($model, "[$index]ticket_id", ['inputOptions' => ['value' => $ticket_id]])->textInput(['readonly' => true, 'class' => 'read-only form-control']);
                        } else {
                            echo $form->field($model, "[$index]ticket_id")->textInput();
                        }
                    ?>
                </div>
            </div>
            <div class="form-group">
                <?= Html::button('Remove', ['class' => 'modal-button-remove btn btn-outline-secondary border-imperial-red imperial-red btn-skinny']); ?>
                <?= Html::button('Add', ['class' => 'modal-button-add btn btn-primary bg-iris border-iris btn-skinny']); ?>
            </div>
        </div>

        <?php endforeach; ?>

    </div>
    <div class="form-group">
        <?= Html::submitButton('Create assets', [
            'id' => 'create-assets',
            'class' => 'mt-4 btn btn-primary bg-pacific-cyan border-pacific-cyan',
            'form' => 'add-asset-form',
        ]); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
