<?php

use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;

/** @var yii\web\View $this */

$this->title = 'WNYRIC iPad Repair Billing Report';
?>
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
            <?= $this->render('date-form', ['startDate' => $startDate, 'endDate' => $endDate]) ?>
        </div>
        <div id="wnyric-ipad-repair-billing-report" class="border-top pt-2 mt-2">
            <span>You are viewing the <strong><?= Html::encode(date('F j, Y', strtotime($startDate))) ?></strong> to <strong><?= Html::encode(date('F j, Y', strtotime($endDate))) ?></strong> report.</span>
            <div class="pt-2 print-button">
                <?= Html::button('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16" aria-hidden="true">
                            <path d="M11 2H9v3h2z"/>
                            <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
                        </svg>&nbsp;Print',
                        ['class' => 'd-inline-block btn btn-primary bg-pacific-cyan border-pacific-cyan text-dark']
                ); ?>
            </div>
            <div>
                <h3>Report</h3>
                <?php if ($model): ?>
                <div class="report">
                    <table class="border table table-striped">
                        <thead>
                            <tr class="hover-row">
                                <th scope="col">District / Asset Make</th>
                                <th scope="col">Ticket ID</th>
                                <th scope="col">Description / Asset Prop Tag</th>
                                <th scope="col">RIC Que Ticket / Part Quantity / Asset Serial #</th>
                                <th scope="col">Date / Part Unit Price</th>
                                <th scope="col">Tech Time</th>
                                <th scope="col">Cost</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($model['districts'] as $district): ?>
                            <tr class="hover-row">
                                <td><?= Html::encode($district['name']) ?></td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <?php foreach($district['tickets'] as $ticket): ?>
                            <tr class="hover-row">
                                <td>&nbsp;</td>
                                <td style="text-align:center;"><?= Html::encode($ticket['id']) ?></td> 
                                <td><?= Html::encode($ticket['summary']) ?></td>
                                <td><?= Html::encode($ticket['description']) ?></td>
                                <td><?= date('F j, Y', strtotime($ticket['modified'])) ?></td>
                                <td><?= Html::encode($ticket['totalLaborHours']) ?></td>
                                <td><?= Html::encode(Yii::$app->formatter->asCurrency($ticket['totalLaborCost'])) ?></td>
                            </tr>
                            <?php foreach($ticket['parts'] as $index => $part): ?>
                            <tr class="hover-row">
                                <td>&nbsp;</td>
                                <td style="text-align:right;">Part #<?= Html::encode($index+1) ?></td> 
                                <td><?= Html::encode($part['part_name']) ?></td>
                                <td><?= Html::encode($part['note']) ?></td>
                                <td><?= Html::encode($part['quantity']) ?></td>
                                <td><?= Html::encode(Yii::$app->formatter->asCurrency($part['unit_price'])) ?></td>
                                <td><?= Html::encode(Yii::$app->formatter->asCurrency($part['quantity'] * $part['unit_price'])) ?></td>
                            </tr>
                            <?php endforeach ?>
                            <?php foreach($ticket['assets'] as $index => $asset): ?>
                            <tr class="hover-row">
                                <td>&nbsp;</td>
                                <td style="text-align:right;">Asset #<?= Html::encode($index+1) ?></td> 
                                <td><?= Html::encode($asset['inventory']['item_description']) ?></td>
                                <td><?= Html::encode($asset['inventory']['new_prop_tag']) ?></td>
                                <td><?= Html::encode($asset['inventory']['serial_number']) ?></td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <?php endforeach ?>
                            <!-- Totals row for each ticket -->
                            <tr class="hover-row">
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td class="fst-italic">Totals for Ticket ID #<?= Html::encode($ticket['id']) ?></td> 
                                <td>Labor Cost: $<?= Html::encode(Yii::$app->formatter->asCurrency($ticket['totalLaborCost'])) ?></td>
                                <td>Parts: $<?= Html::encode(Yii::$app->formatter->asCurrency($ticket['totalPartsCost'])) ?></td>
                                <td>Labor Hours: <?= Html::encode(Yii::$app->formatter->asCurrency($ticket['totalLaborHours'])) ?></td>
                                <td>Total: $<?= Html::encode(Yii::$app->formatter->asCurrency($ticket['totalCost'])) ?></td>
                            </tr>
                            <?php endforeach ?>
                            <!-- Totals row for each district -->
                            <tr class="hover-row">
                                <td class="fst-italic" style="text-align:right;">Totals for <?= Html::encode($district['name']) ?></td> 
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>Labor Cost: $<?= Html::encode(Yii::$app->formatter->asCurrency($district['totalLaborCost'])) ?></td>
                                <td>Parts: $<?= Html::encode(Yii::$app->formatter->asCurrency($district['totalPartsCost'])) ?></td>
                                <td>Labor Hours: <?= Html::encode(Yii::$app->formatter->asCurrency($district['totalLaborHours'], 2)) ?></td>
                                <td>Total: $<?= Html::encode(Yii::$app->formatter->asCurrency($district['totalCost'])) ?></td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                        <tfoot>
                            <tr class="hover-row">
                                <td class="fst-italic" scope="row" style="text-align:right;">Grand Totals</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <?php
                                    $totalPartsCost = 0;
                                    $totalTechTime = 0;
                                    $totalCost = 0;
                                    foreach ($model['districts'] as $district) {
                                        $totalPartsCost += array_sum(array_column($district['tickets'], 'totalPartsCost'));
                                        $totalTechTime += array_sum(array_column($district['tickets'], 'totalLaborHours'));
                                        $totalCost += array_sum(array_column($district['tickets'], 'totalCost'));
                                    } 
                                ?>
                                <td><?= Html::encode(Yii::$app->formatter->asCurrency($totalPartsCost)) ?><!-- Part * quantity = parts total. --></td>
                                <td><?= Html::encode(Yii::$app->formatter->asCurrency($totalTechTime)) ?><!-- tech time total. --></td>
                                <td class="fw-bold">$<?= Html::encode(Yii::$app->formatter->asCurrency($totalCost)) ?><!-- cost total. --></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <?php else: ?>
                <div>
                    Nothing to report.
                </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>