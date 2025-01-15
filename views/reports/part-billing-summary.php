<?php

use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\jui\DatePicker;

/** @var yii\web\View $this */

$this->title = 'Part Billing Summary';
?>
<div class="reports-billing-summary">
    <div>
        <div class="title-icon d-flex align-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="2rem" height="2rem" fill="currentColor" class="bi bi-bar-chart-line-fill" viewBox="0 0 16 16" aria-hidden="true">
                <path d="M11 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h1V7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7h1z"/>
            </svg>
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <p>This report returns the total costs of parts each district or divison used in the specified month and year.</p>
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
                <?php
                    echo ExportMenu::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => $gridColumns,
                        'dropdownOptions' => [
                            'label' => 'Export All',
                            'class' => 'btn btn-outline-secondary btn-default'
                        ]
                    ]) . "<hr>\n" .
                    GridView::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => $gridColumns,
                    ]); 
                ?>
            </div>
        </div>
    </div>
</div>