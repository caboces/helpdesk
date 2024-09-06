<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\JobCategory $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="job-category-form">

    <?php $form = ActiveForm::begin(); ?>

    <!-- action buttons -->
    <div class='container-fluid p-2 | bg-dark shadow-sm'>
        <?= Html::a('Back', Yii::$app->request->referrer, ['class' => 'btn btn-secondary']); ?>
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary bg-pacific-cyan border-pacific-cyan text-dark']) ?>
    </div>

    <h2>General details</h2>
    <p>Details pertaining to the category</p>

    <div class="question-box">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'description')->textarea(['maxlength' => true, 'rows' => 3]) ?>
    </div>

    <!-- action buttons -->
    <div class='container-fluid border border-subtle py-2 | bg-light'>
        <?= Html::a('Back', Yii::$app->request->referrer, ['class' => 'btn btn-secondary']); ?>
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary bg-pacific-cyan border-pacific-cyan text-dark']); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>