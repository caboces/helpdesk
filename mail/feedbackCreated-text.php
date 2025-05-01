<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\TicketDraft $ticketDraft */

?>
Hello <?= Html::encode($feedback->name) ?>,

We appreciate you providng us feedback with our Helpdesk team. Should we be concerned about the quality of help provided, our team will contact you at the provided email address or phone number.

Feedback Details:

Name: <?= Html::encode($feedback->name) ?>
Email: <?= Html::encode($feedback->email) ?>
Phone: <?= Html::encode($feedback->phone) ?>
Date Submitted: <?= Html::encode(Yii::$app->dateUtils->asDate($feedback->created)) ?> EST
Note: <?= Html::encode($feedback->note) ?>

CABOCES Helpdesk