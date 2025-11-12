<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\TicketDraft $ticketDraft */

?>
<div>
    <p>Hello <?= Html::encode($feedback->name) ?>,</p>
    <br />
    <p>We appreciate you providng us feedback with our Helpdesk team. Should we be concerned about the quality of help provided, our team will contact you at the provided email address or phone number.</p>
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