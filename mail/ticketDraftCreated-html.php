<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\TicketDraft $ticketDraft */

?>
<div>
    <p>Hello,</p>
    <p>We have received your ticket. You will be updated by our techs shortly.</p>
    <p><?= Html::encode($ticketDraft->requestor) ?></p>
    <p><?= Html::encode($ticketDraft->summary) ?></p>
    <p><?= Html::encode($ticketDraft->description) ?></p>
</div>