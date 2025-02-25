<?php

use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;

/** @var yii\web\View $this */

$this->title = 'WNYRIC iPad Repair Labor Report';
?>
<div class="wnyric-ipad-repair-labor-report">
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
        <div class="border-top pt-2 mt-2">
            <span>You are viewing the <strong><?= Html::encode(date('F j, Y', strtotime($startDate))) ?></strong> to <strong><?= Html::encode(date('F j, Y', strtotime($endDate))) ?></strong> report.</span>
            <div>
                <h3>Report</h3>
                <div class="report">
                    <?php if ($model['districts']): ?>
                    <table class="border table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Ticket ID</th>
                                <th scope="col">District</th>
                                <th scope="col">Job Description</th>
                                <th scope="col">RIC Que Ticket</th>
                                <th scope="col">Tech Time</th>
                                <th scope="col">Labor Cost</th>
                                <th scope="col">Who</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($model['districts'] as $district): ?>
                        <?php foreach($district['tickets'] as $ticket): ?>
                        <tr>
                            <td><?= Html::encode($ticket['id']) ?></td>
                            <td><?= Html::encode($district['name']) ?></td>
                            <td><?= Html::encode($ticket['summary']) ?></td>
                            <td>&nbsp;</td>
                            <td><?= Html::encode(number_format($ticket['totalTechTime'], 2)) ?></td>
                            <td><?= Html::encode(Yii::$app->formatter->asCurrency($ticket['totalLaborCost'])) ?></td>
                            <td><?= Html::encode($ticket['requester']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td class="fst-italic">Total for <?= Html::encode($district['name']) ?></td>
                            <td><?= Html::encode(number_format($district['totalTechTime'], 2)) ?></td>
                            <td><?= Html::encode(Yii::$app->formatter->asCurrency($district['totalLaborCost'])) ?></td>
                            <td>&nbsp;</td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="fw-bold fst-italic">
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>Total for WNYRIC</td>
                                <td><?= Html::encode(number_format($model['totalTechTime'], 2)) ?></td>
                                <td><?= Html::encode(Yii::$app->formatter->asCurrency($model['totalLaborCost'])) ?></td>
                                <td>&nbsp;</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <?php else: ?>
                <div>
                    Nothing to report.
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>