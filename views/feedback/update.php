<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Feedback $model */

$this->title = 'Update Feedback: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Feedbacks', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="feedback-update">
    <div class="title-icon d-flex align-items-center">
        <i class="fa-solid fa-comment fa-2xl"></i>
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
