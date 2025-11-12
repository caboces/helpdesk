<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\TicketDraft $ticketDraft */

$createTicketLink = Yii::$app->urlManager->createAbsoluteUrl(['/ticket/create', 'ticketDraftId' => $ticketDraft->id]);
?>
Hello,

A new Ticket Request was submitted to the queue.

Requester: <?= Html::encode($ticketDraft->requestor) ?>
Summary: <?= Html::encode($ticketDraft->summary) ?>
Description: <?= Html::encode($ticketDraft->description) ?>

Click the link below to create a ticket from it.
To avoid confusion, make sure only one person makes a ticket.

<?= Html::encode($createTicketLink) ?>