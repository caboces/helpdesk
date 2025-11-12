<?php 

use yii\helpers\Html;
use app\models\JobStatus;

?>

Hello <?= Html::encode($username) ?>,

A ticket's status has changed to: <?= Html::encode(strtolower(JobStatus::findOne(['id' => $ticket->job_status_id])->name)) ?>.

Ticket ID<?="\t"?><?= Html::encode($ticket->id) ?>
Summary<?="\t"?><?= Html::encode($ticket->summary) ?>
Requester<?="\t"?><?= Html::encode($ticket->requester) ?>
Primary Tech<?="\t"?><?= Html::encode($primary_tech) ?>
Location<?="\t"?><?= Html::encode($ticket->location) ?>
Category<?="\t"?><?= Html::encode($jobLabels['job_category']) ?>
Priority<?="\t"?><?= Html::encode($jobLabels['job_priority']) ?>
Status<?="\t"?><?= Html::encode($jobLabels['job_status']) ?>
Type<?="\t"?><?= Html::encode($jobLabels['job_type']) ?>
Date Submitted<?="\t"?><?= Html::encode($ticket->created) ?>