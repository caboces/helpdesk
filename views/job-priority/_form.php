<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\JobPriority $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="job-priority-form">

    <?php $form = ActiveForm::begin(); ?>

    <!-- action buttons -->
    <div class='container-fluid p-2 | bg-dark shadow-sm'>
        <?= Html::a('Back', Yii::$app->request->referrer, ['class' => 'btn btn-secondary']); ?>
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary bg-pacific-cyan border-pacific-cyan']) ?>
    </div>

    <h2>General details</h2>
    <p>Details pertaining to the priority</p>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['maxlength' => true, 'rows' => 3]) ?>

    <?php ActiveForm::end(); ?>

</div>