<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\PartType $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="part-type-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, "created_by", [
        'template' => '{input}',
        'options' => ['tag' => false],
        'inputOptions' => ['value' => Yii::$app->user->id]
    ])->hiddenInput([
        'readonly' => true, 
    ])->label(false) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label('Part Type Name') ?>

    <?= $form->field($model, 'description')->textarea(['maxlength' => true])->label('Part Type Description') ?>

    <div class="form-group">
        <?= Html::submitButton('Create Part Type', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
