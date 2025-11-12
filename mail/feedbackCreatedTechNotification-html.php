<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\User $tech */
/** @var common\models\TicketDraft $ticketDraft */

?>
<div>
    <p>Hello <?= Html::encode($tech->fname) ?>,</p>
    <br />
    <p>Someone has left some feedback for the Helpdesk!</p>
    <br />
    <p>Feedback Details:</p>
    <br />
    <p>Name: <?= Html::encode($feedback->name) ?></p>
    <p>Email: <?= Html::encode($feedback->email) ?></p>
    <p>Phone: <?= Html::encode($feedback->phone) ?></p>
    <p>Date Submitted: <?= Html::encode(Yii::$app->dateUtils->asDate($feedback->created)) ?> EST</p>
    <p>Note: <?= Html::encode($feedback->note) ?></p>
    <br />
    <p>CABOCES Helpdesk</p>
</div>