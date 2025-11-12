<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Department $model */

$this->title = 'Create Department';
$this->params['breadcrumbs'][] = ['label' => 'Departments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="department-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>Create a new department using the form below.</p>
    <p>Please use as short as a name as possible when creating a department.</p>
    <p>The description is optional.</p>
    <?= $this->render('_form', [
        'model' => $model,
        'divisionsOptions' => $divisionsOptions,
    ]) ?>

</div>
