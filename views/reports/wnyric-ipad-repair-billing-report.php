<?php

use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;

/** @var yii\web\View $this */

$this->title = 'WNYRIC iPad Repair Billing Report';
?>
<style>
    .hover-row:hover {
        background:color#ccc;
    }
</style>
<div class="wnyric-ipad-repair-billing-report">
    <div>
        <div class="title-icon d-flex align-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="2rem" height="2rem" fill="currentColor" class="bi bi-bar-chart-line-fill" viewBox="0 0 16 16" aria-hidden="true">
                <path d="M11 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h1V7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7h1z"/>
            </svg>
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <p>This report returns the labor time and cost of repairing iPads for WNYRIC districts.</p>
    </div>
    <div>
        <div>
            <strong>Select a date range</strong>
            <?= $this->render('date-form', ['startDate' => $startDate, 'endDate' => $endDate]) ?>
        </div>
        <div class="border-top pt-2 mt-2">
            <span>You are viewing the <strong><?= Html::encode(date('F j, Y', strtotime($startDate))) ?></strong> to <strong><?= Html::encode(date('F j, Y', strtotime($endDate))) ?></strong> report.</span>
            <div>
                <h3>Report</h3>
                <table class="table table-striped">
                    <thead>
                        <tr class="hover-row">
                            <th scope="col">District / Asset Make</th>
                            <th scope="col">Ticket ID</th>
                            <th scope="col">Description / Asset Model</th>
                            <th scope="col">RIC Que Ticket / Part Quantity / Asset Serial #</th>
                            <th scope="col">Date / Part Unit Price</th>
                            <th scope="col">Tech Time</th>
                            <th scope="col">Cost</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($districts as $district): ?>
                        <tr class="hover-row">
                            <td><?= Html::encode($district->name) ?></td> 
                        </tr>
                        <?php foreach($district->tickets as $ticket): ?>
                        <tr class="hover-row">
                            <td><?= Html::encode($ticket->id) ?></td> 
                            <td><?= Html::encode($ticket->summary) ?></td>
                            <td><?= date('F j, Y', strtotime($ticket->created)) ?></td>
                            <td><?= Html::encode(0/* $ticket->tech_time*/) ?></td>
                            <td><?= Html::encode(0/*$ticket->cost*/) ?></td>
                        </tr>
                        <?php foreach($ticket->parts as $index => $part): ?>
                        <tr class="hover-row">
                            <td>Part #<?= Html::encode($index) ?></td> 
                            <td><?= Html::encode($part->description) ?></td>
                            <td><?= Html::encode($part->quantity) ?></td>
                            <td><?= Html::encode($part->unit_price) ?></td>
                            <td><?= Html::encode($part->quantity * $part->unit_price) ?></td>
                        </tr>
                        <?php endforeach ?>
                        <?php foreach($ticket->assets as $index => $asset): ?>
                        <tr class="hover-row">
                            <td>Asset #<?= Html::encode($index) ?></td> 
                            <td><?= Html::encode($asset->item_description) ?></td>
                            <td><?= Html::encode($asset->new_prop_tag) ?></td>
                            <td><?= Html::encode($asset->serial_number) ?></td>
                            <td><?= Html::encode($asset->po) ?></td>
                        </tr>
                        <?php endforeach ?>
                        <!-- Totals row for each ticket -->
                        <tr class="hover-row">
                            <td class="fw-bold">Total for Ticket ID #<?= Html::encode($ticket->id) ?></td> 
                            <td>Labor Hours: <?= Html::encode(0/*$ticket->labor_hours*/) ?></td>
                            <td>Labor Cost: <?= Html::encode(0/*$ticket->labor_cost*/) ?></td>
                            <td>Parts: <?= Html::encode(0/*$ticket->parts_cost*/) ?></td>
                            <td>Total: <?= Html::encode(0/*$ticket->total*/) ?></td>
                        </tr>
                        <?php endforeach ?>
                        <!-- Totals row for each district -->
                        <tr class="hover-row">
                            <td class="fw-bold">Total for <?= Html::encode($district->name) ?></td> 
                            <td>Labor Hours: <?= Html::encode(0) ?></td>
                            <td>Labor Cost: <?= Html::encode(0) ?></td>
                            <td>Parts: <?= Html::encode(0) ?></td>
                            <td>Total: <?= Html::encode(0) ?></td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                    <tfoot>
                        <tr class="hover-row">
                            <td class="fw-italic" scope="row">Grand Totals</td>
                            <td><?= Html::encode(0/*$grand_parts_total*/) ?><!-- Part * quantity = parts total. --></td>
                            <td><?= Html::encode(0/*$grand_tech_time_total*/) ?><!-- tech time total. --></td>
                            <td><?= Html::encode(0/*$grand_cost_total*/) ?><!-- cost total. --></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>