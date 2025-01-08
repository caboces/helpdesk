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
        <div class="duplicate-input-group question-box-no-trim">
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
                    <?= $form->field($model, 'ticket_id', ['inputOptions' => ['value' => $ticket_id]])->textInput(['readonly' => true, 'class' => 'read-only form-control']) ?>
                </div>
            </div>
            <div class="form-group">
                <?= Html::button('Remove', ['class' => 'modal-button-remove btn btn-outline-secondary border-imperial-red imperial-red btn-skinny']); ?>
                <?= Html::button('Add', ['class' => 'modal-button-add btn btn-primary bg-iris border-iris btn-skinny']); ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
