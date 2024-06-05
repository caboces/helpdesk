<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\User $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <!-- action buttons -->
    <div class='container-fluid p-2 | bg-dark shadow-sm'>
        <?= Html::a('Back', Yii::$app->request->referrer, ['class' => 'btn btn-secondary']); ?>
        <?= Html::submitButton('Save changes', ['class' => 'btn btn-primary bg-pacific-cyan border-pacific-cyan']) ?>
    </div>

    <h2>General details</h2>
    <p>Details pertaining to the user</p>
    <div class="question-box">
        <div class="row">
            <div class="col-md-6">
                <!-- uname + email not working, idk why -->
                <?= $form->field($user, 'username')->textInput([
                    'disabled' => 'disabled',
                ]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($user, 'email')->textInput([
                    'disabled' => 'disabled',
                ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($user, 'status')->dropDownList(
                    (['10' => 'Active', '9' => 'Inactive']),
                    ['prompt' => '-Choose status-']
                ); ?>
            </div>
        </div>
    </div>
    <!-- action buttons -->
    <div class='secondary-action-button-bar'>
        <?= Html::a('Back', Yii::$app->request->referrer, ['class' => 'btn btn-secondary']); ?>
        <?= Html::submitButton('Save changes', ['class' => 'btn btn-primary bg-pacific-cyan border-pacific-cyan']); ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>