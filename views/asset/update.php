<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\TicketEquipment $model */

$this->title = 'Update TicketEquipment: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ticket Equipment', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ticket-equipment-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
