<?php

use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;

/** @var yii\web\View $this */

$this->title = 'Technicians By Call Type Report';
?>
<div class="technicians-by-call-type-report">
    <div>
        <div class="title-icon d-flex align-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="2rem" height="2rem" fill="currentColor" class="bi bi-bar-chart-line-fill" viewBox="0 0 16 16" aria-hidden="true">
                <path d="M11 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h1V7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7h1z"/>
            </svg>
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <p>This report returns each technicians's activity in tickets grouped by job type.</p>
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
                    <?php if ($model): ?>
                    <table class="border table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Tech Name</th>
                                <th scope="col">Customer Type</th>
                                <th scope="col">Division</th>
                                <th scope="col">Department</th>
                                <th scope="col">District</th>
                                <th scope="col">Building</th>
                                <th scope="col">Tech Time</th>
                                <th scope="col">Overtime</th>
                                <th scope="col">Travel Time</th>
                                <th scope="col">Itinerate Time</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($model as $row): ?>
                        <tr>
                            <td><?= Html::encode($row['first_name']) ?> <?= Html::encode($row['last_name']) ?></td>
                            <td><?= Html::encode($row['code']) ?></td>
                            <td><?= Html::encode($row['division_name']) ?></td>
                            <td><?= Html::encode($row['department_name']) ?></td>
                            <td><?= Html::encode($row['district_name']) ?></td>
                            <td><?= Html::encode($row['building_name']) ?></td>
                            <td><?= Html::encode(number_format($row['total_tech_time'], 2)) ?></td>
                            <td><?= Html::encode(number_format($row['total_overtime'], 2)) ?></td>
                            <td><?= Html::encode(number_format($row['total_travel_time'], 2)) ?></td>
                            <td><?= Html::encode(number_format($row['total_itinerate_time'], 2)) ?></td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
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