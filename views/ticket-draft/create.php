<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\TicketDraft $model */

$this->title = 'Create Ticket Draft';
$this->params['breadcrumbs'][] = ['label' => 'Ticket Drafts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-draft-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>Please fill out the information below to request a new ticket.</p>
    <small>Please be sure to enter in the correct contact information as it will be used to contact you.</small>

    <?= $this->render('_form', [
        'model' => $model,
        'jobTypeCategoryData' => $jobTypeCategoryData,
        'jobTypes' => $jobTypes,
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
