<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\TicketNote $model */

$this->title = 'Update Ticket Note: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ticket Notes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ticket-note-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
