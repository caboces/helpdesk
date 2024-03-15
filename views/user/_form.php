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
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary bg-pacific-cyan border-pacific-cyan']) ?>
    </div>

    <h2>General details</h2>
    <p>Details pertaining to the user</p>
    <div class="question-box">
        <!-- uname + email not working, idk why -->
        <?= $form->field($user, 'username')->textInput([
            'disabled' => 'disabled',
        ]) ?>

        <?= $form->field($user, 'email')->textInput([
            'disabled' => 'disabled',
        ]) ?>

        <?= $form->field($user, 'status')->dropDownList(
            (['10' => 'Active', '9' => 'Inactive']),
            ['prompt' => '-Choose status-']
        ); ?>
    </div>
    <!-- action buttons -->
    <div class='container-fluid border border-subtle py-2 | bg-light'>
        <?= Html::a('Back', Yii::$app->request->referrer, ['class' => 'btn btn-secondary']); ?>
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary bg-pacific-cyan border-pacific-cyan']); ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>