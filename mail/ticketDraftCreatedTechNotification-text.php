<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\TicketDraft $ticketDraft */

$createTicketLink = Yii::$app->urlManager->createAbsoluteUrl(['/ticket/create', 'ticketDraftId' => $ticketDraft->id]);
?>
Hello,

A new Ticket Request was submitted to the queue.

<?= Html::encode($ticketDraft->requestor) ?>
<?= Html::encode($ticketDraft->summary) ?>
<?= Html::encode($ticketDraft->description) ?>

Click the link below to create a ticket from it.
To avoid confusion, make sure only one person makes a ticket.

<?= Html::encode($createTicketLink) ?>