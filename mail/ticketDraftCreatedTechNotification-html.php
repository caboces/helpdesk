<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\TicketDraft $ticketDraft */

$createTicketLink = Yii::$app->urlManager->createAbsoluteUrl(['/ticket/create', 'ticketDraftId' => $ticketDraft->id]);
?>
<div>
    <p>Hello,</p>
    <p>A new Ticket Request was submitted to the queue.</p>
    <p><?= Html::encode($ticketDraft->requestor) ?></p>
    <p><?= Html::encode($ticketDraft->summary) ?></p>
    <p><?= Html::encode($ticketDraft->description) ?></p>
    <p>Click the link below to create a ticket from it.</p>
    <p>To avoid confusion, make sure only one person makes a ticket.</p>
    <p><?= Html::encode($createTicketLink) ?></p>
</div>