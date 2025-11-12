<?php

/** @var yii\web\View $this */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var app\models\Feedback $model */
/** @var yii\widgets\ActiveForm $form */
?>

<script>
    function reCaptchaOnSubmit(token) {
        $('#feedback-form').submit()
    }
</script>
<div class="feedback-form">

    <?php $form = ActiveForm::begin([
        'id' => 'feedback-form',
        'enableClientValidation' => true,
        'validateOnSubmit' => true,
    ]); ?>

    <div>
        <div class="question-box">
            <div class="row">
                <div class="col-md-3">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'ticket_id')->textInput() ?>
                </div>
            </div>
        </div>
        <div class="question-box">
            <div class="row">
                <div class="col">
                    <?= $form->field($model, 'note')->textarea(['rows' => 6]) ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Honeypot fields -->
    <?= Html::input('text', 'vc090h3n', null, ['hidden' => true]) ?>
    
    <div class="form-group">
        <p>Please note that we will not be accepting file uploads in this form due to security limitations.</p>
        <?= Html::submitButton('Submit Feedback to CABOCES', [
            'class' => 'btn btn-success g-recaptcha', 
            'data-sitekey' => '6LcEMsEqAAAAACHqBOkDNZDP7CFW2JjMLvPdN7IQ',
            'data-callback' => 'reCaptchaOnSubmit',
            'data-action' => 'submit',
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
