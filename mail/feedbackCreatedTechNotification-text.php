<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var User $tech */
/** @var TicketDraft $ticketDraft */

?>
Hello <?= Html::encode($tech->fname) ?>,

Someone has left some feedback for the Helpdesk!

Feedback Details:

Name: <?= Html::encode($feedback->name) ?>
Email: <?= Html::encode($feedback->email) ?>
Phone: <?= Html::encode($feedback->phone) ?>
Date Submitted: <?= Html::encode(Yii::$app->dateUtils->asDate($feedback->created)) ?> EST
Note: <?= Html::encode($feedback->note) ?>

CABOCES Helpdesk