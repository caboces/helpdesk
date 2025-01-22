<?php

use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;

/** @var yii\web\View $this */

$this->title = 'Detailed Monthly Report';
?>
<div class="detailed-monthly-report">
    <div>
        <div class="title-icon d-flex align-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="2rem" height="2rem" fill="currentColor" class="bi bi-bar-chart-line-fill" viewBox="0 0 16 16" aria-hidden="true">
                <path d="M11 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h1V7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7h1z"/>
            </svg>
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <p>This report returns each technicians's activity in tickets within the specified date range.</p>
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
                    <?php if ($model['users']): ?>
                    <table class="border table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Tech Name</th>
                                <th scope="col">Ticket ID</th>
                                <th scope="col">Customer</th>
                                <th scope="col">Entry Date</th>
                                <th scope="col">Description</th>
                                <th scope="col">Tech Time</th>
                                <th scope="col">Overtime</th>
                                <th scope="col">Travel Time</th>
                                <th scope="col">Itinerate Time</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($model['users'] as $user): ?>
                        <tr>
                            <td><?= Html::encode($user['fname']) ?> <?= Html::encode($user['lname']) ?></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td><?= Html::encode(number_format($user['totalTechTime'], 2)) ?></td>
                            <td><?= Html::encode(number_format($user['totalOvertime'], 2)) ?></td>
                            <td><?= Html::encode(number_format($user['totalTravelTime'], 2)) ?></td>
                            <td><?= Html::encode(number_format($user['totalItinerateTime'], 2)) ?></td>
                        </tr>
                        <?php foreach($user['tickets'] as $ticket): ?>
                        <tr>
                            <td>&nbsp;</td>
                            <td><?= Html::encode($ticket['id']) ?></td>
                            <td><?= Html::encode($ticket['customer_name']) ?></td>
                            <td><?= Html::encode(date('F j, Y', strtotime($ticket['created']))) ?></td>
                            <td><?= Html::encode($ticket['summary']) ?></td>
                            <td><?= Html::encode(number_format($ticket['totalTechTime'], 2)) ?></td>
                            <td><?= Html::encode(number_format($ticket['totalOvertime'], 2)) ?></td>
                            <td><?= Html::encode(number_format($ticket['totalTravelTime'], 2)) ?></td>
                            <td><?= Html::encode(number_format($ticket['totalItinerateTime'], 2)) ?></td>
                        </tr>
                        <?php foreach($ticket['time_entries'] as $time_entry): ?>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td><?= Html::encode(date('F j, Y', strtotime($time_entry['entry_date']))) ?></td>
                            <td><?= Html::encode($time_entry['note']) ?></td>
                            <td><?= Html::encode($time_entry['tech_time']) ?></td>
                            <td><?= Html::encode($time_entry['overtime']) ?></td>
                            <td><?= Html::encode($time_entry['travel_time']) ?></td>
                            <td><?= Html::encode($time_entry['itinerate_time']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endforeach; ?>
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