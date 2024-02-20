<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \frontend\models\SignupForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Create New User';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <div class="title-icon d-flex align-items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="2rem" height="2rem" fill="currentColor" class="bi bi-palette-fill" viewBox="0 0 16 16">
            <path d="M12.433 10.07C14.133 10.585 16 11.15 16 8a8 8 0 1 0-8 8c1.996 0 1.826-1.504 1.649-3.08-.124-1.101-.252-2.237.351-2.92.465-.527 1.42-.237 2.433.07M8 5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m4.5 3a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3M5 6.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m.5 6.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3" />
        </svg>
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

    <!-- action buttons -->
    <div class='container-fluid p-2 | bg-dark shadow-sm'>
        <?= Html::a('Back', Yii::$app->request->referrer, ['class' => 'btn btn-secondary']); ?>
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary bg-pacific-cyan border-pacific-cyan']) ?>
    </div>

    <h2>General details</h2>
    <p>Details pertaining to the user</p>

    <!-- $form->field($model, 'username')->textInput(['autofocus' => true]) -->
    <div class="border border-2 rounded-1 border-subtle bg-light p-3 my-3">
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'fname')->textInput()->label('First name') ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'lname')->textInput()->label('Last name') ?>
            </div>
        </div>

        <?= $form->field($model, 'email') ?>

    </div>
    <div class="border border-2 rounded-1 border-subtle bg-light p-3 my-3">
        <!-- this is where i'm putting the auto generated username -->
        <?= $form->field($model, 'username')->textInput(['disabled' => true])->label('Username') ?>

        <?= $form->field($model, 'password')->passwordInput() ?>
    </div>

    <!-- action buttons -->
    <div class='container-fluid border border-subtle py-2 | bg-light'>
        <?= Html::a('Back', Yii::$app->request->referrer, ['class' => 'btn btn-secondary']); ?>
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary bg-pacific-cyan border-pacific-cyan']); ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>