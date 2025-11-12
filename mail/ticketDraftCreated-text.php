<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\TicketDraft $ticketDraft */

?>
Hello,

We have received your ticket. You will be updated by our techs shortly.

<?= Html::encode($ticketDraft->requestor) ?>
<?= Html::encode($ticketDraft->summary) ?>
<?= Html::encode($ticketDraft->description) ?>