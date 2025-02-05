<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\TicketNote $model */

$this->title = 'Create Ticket Journal Entry';
$this->params['breadcrumbs'][] = ['label' => 'Ticket Journal Entries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-note-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'ticket_id' => $ticket_id,
    ]) ?>

</div>
