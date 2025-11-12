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
        <i class="fa-solid fa-clock fa-2xl"></i>
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <?= $this->render('_form', [
        'models' => $models,
        'ticket_id' => $ticket_id
    ]) ?>

</div>
