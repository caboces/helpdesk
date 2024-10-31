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

    <div class="assets-container">
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
                    <?= $form->field($model, 'asset_tag')->textInput() ?>
                </div>
                <div class="col">
                    <?= $form->field($model, 'ticket_id', ['inputOptions' => ['value' => $ticket->id]])->textInput(['readonly' => true, 'class' => 'read-only form-control']) ?>
                </div>
            </div>
            <div class="form-group">
                <?= Html::button('Remove', ['class' => 'btn btn-outline-secondary border-imperial-red imperial-red btn-skinny']); ?>
                <?= Html::button('Duplicate', ['class' => 'btn btn-primary bg-iris border-iris btn-skinny']); ?>
            </div>
        </div>
    </div>


    <div class="form-group">
        <?= Html::submitButton('Confirm asset', [
            'id' => 'confirm-asset',
            'class' => 'mt-4 btn btn-primary bg-pacific-cyan border-pacific-cyan',
            'form' => 'add-asset-form',
        ]); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
