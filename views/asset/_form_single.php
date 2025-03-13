<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Asset $model */
/** @var yii\bootstrap5\ActiveForm $form */

?>

<div class="single-asset-form">

    <?php $form = ActiveForm::begin([
        'id' => 'single-asset-form',
        'enableClientValidation' => true,
        'validateOnSubmit' => true,
    ]); ?>

    <!-- Hidden ticket id field if it is filled -->
    <div id="asset-box" class="asset-container">
        <h3>Update Asset</h3>
        <div class="question-box-no-trim">
            <?= $form->field($model, "last_modified_by_user_id", [
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
                    <?= $form->field($model, "new_prop_tag")->textInput() ?>
                </div>
                <div class="col">
                    <?= $form->field($model, "po_number")->textInput() ?>
                </div>
                <div class="col">
                    <?= $form->field($model, "ticket_id")->textInput(); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <!-- Disable submitting the asset if we don't know the ticket id -->
        <?= Html::submitButton('Submit', [
            'id' => 'create-assets',
            'class' => 'mt-4 btn btn-primary bg-pacific-cyan border-pacific-cyan',
            'form' => 'single-asset-form',
        ]); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
