<?php

use yii\helpers\Html;
use app\models\JobStatus;

?>
<div>
    <p>Hello <?= Html::encode($username) ?>, from Help Desk 2.0,</p>
    <p>A ticket's status has changed to: <?= Html::encode(strtolower(JobStatus::findOne(['id' => $ticket->job_status_id])->name)) ?>.</p>
    <table>
        <tr>
            <td>Ticket ID</td>
            <td><?= Html::encode($ticket->id) ?></td>
        </tr>
        <tr>
            <td>Summary</td>
            <td><?= Html::encode($ticket->summary) ?></td>
        </tr>
        <tr>
            <td>Requester</td>
            <td><?= Html::encode($ticket->requester) ?></td>
        </tr>
        <tr>
            <td>Primary Tech</td>
            <td><?= Html::encode($primary_tech) ?></td>
        </tr>
        <tr>
            <td>Location</td>
            <td><?= Html::encode($ticket->location) ?></td>
        </tr>
        <tr>
            <td>Category</td>
            <td><?= Html::encode($jobLabels['job_category']); ?></td>
        </tr>
        <tr>
            <td>Priority</td>
            <td><?= Html::encode($jobLabels['job_priority']) ?></td>
        </tr>
        <tr>
            <td>Status</td>
            <td><?= Html::encode($jobLabels['job_status']) ?></td>
        </tr>
        <tr>
            <td>Type</td>
            <td><?= Html::encode($jobLabels['job_type']) ?></td>
        </tr>
        <tr>
            <td>Date Submitted</td>
            <td><?= Html::encode($ticket->created) ?></td>
        </tr>
    </tr>
</table>