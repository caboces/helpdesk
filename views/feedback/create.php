<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Feedback $model */

$this->title = 'Create Feedback';
/** Disable breadcrumbs as this is public facing application */
// $this->params['breadcrumbs'][] = ['label' => 'Feedbacks', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="feedback-create">
    <div class="title-icon d-flex align-items-center">
        <i class="fa-solid fa-comment fa-2xl"></i>
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    
    <p>Please fill out the information below to provide feedback to the CABOCES Helpdesk.</p>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
