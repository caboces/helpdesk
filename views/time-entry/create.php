<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\TimeEntry $model */

$this->title = 'New Time Entry';
$this->params['breadcrumbs'][] = ['label' => 'Time Entries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="time-entry-create">

    <div class="title-icon d-flex align-items-center">
        <svg aria-label="hidden" xmlns="http://www.w3.org/2000/svg" width="2rem" height="2rem" fill="currentColor" class="bi bi-clock-fill" viewBox="0 0 16 16">
            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z"/>
        </svg>
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
        'ticket' => $ticket
    ]) ?>

</div>
