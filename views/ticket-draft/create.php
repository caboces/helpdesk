<?php

use app\assets\FormAsset;
use app\assets\TicketAsset;
use app\assets\TicketDraftCreateAsset;
use yii\helpers\Html;
use yii\helpers\Inflector;

/** @var yii\web\View $this */
/** @var app\models\TicketDraft $model */

$this->title = 'Create Ticket Request';
$this->params['breadcrumbs'][] = ['label' => 'Ticket Drafts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

FormAsset::register($this);
TicketAsset::register($this);
TicketDraftCreateAsset::register($this);
?>
<div class="ticket-draft-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>Please fill out the information below to request a new ticket.</p>
    <small>Please be sure to enter in the correct contact information as it will be used to contact you.</small>
    
    <!-- Ticket Draft Errors -->
    <?php if (Yii::$app->session->hasFlash('ticketDraftErrors')): ?>
        <?= $this->render('ticket-draft-field-error', [
            'errorType' => 'ticketDraftErrors',
            'errorTitle' => 'Ticket Draft',
        ]); ?>
    <?php endif; ?>

    <!-- Ticket Draft text error -->
    <?php if (Yii::$app->session->hasFlash('textError')): ?>
        <div class="alert alert-danger">
            <strong><?= Html::encode(Yii::$app->session->getFlash('textError')) ?></strong>
        </div>
    <?php endif; ?>

    <!-- Ticket Draft creation Success -->
    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success">
            <strong><?= Html::encode(Yii::$app->session->getFlash('success')) ?></strong>
        </div>
    <?php endif; ?>

    <?= $this->render('_form', [
        'model' => $model,
        // customers
        'customerTypes' => $customerTypes,
        'districts' => $districts,
        'departments' => $departments,
        'divisions' => $divisions,
        'buildings' => $buildings,
        // customer buildings
        'departmentBuildingData' => $departmentBuildingData,
        'districtBuildingData' => $districtBuildingData,
    ]) ?>

</div>
