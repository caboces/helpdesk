<?php

use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/** @var yii\web\View $this */

$this->title = 'Support and Repair Labor Billing Report';
?>
<div class="support-and-repair-labor-billing">
    <div>
        <div class="title-icon d-flex align-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="2rem" height="2rem" fill="currentColor" class="bi bi-bar-chart-line-fill" viewBox="0 0 16 16" aria-hidden="true">
                <path d="M11 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h1V7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7h1z"/>
            </svg>
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <p>This report returns the reported tech times for either tech labor or visual production by division, district, department, and building.</p>
    </div>
    <div>
        <div>
            <?= $this->render('date-form', ['startDate' => $startDate, 'endDate' => $endDate]) ?>
            <!-- Button trigger modal -->
            <button type="button" class="d-inline-block btn btn-primary" data-bs-toggle="modal" data-bs-target="#labor-rates-modal">
                View labor rates
            </button>
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
            <div>
                <h3>Totals</h3>
                <?php
                    echo ExportMenu::widget([
                        'dataProvider' => $totalsDataProvider,
                        'columns' => $totalsColumns,
                        'dropdownOptions' => [
                            'label' => 'Export All',
                            'class' => 'btn btn-outline-secondary btn-default'
                        ]
                    ]) . "<hr>\n" .
                    GridView::widget([
                        'dataProvider' => $totalsDataProvider,
                        'columns' => $totalsColumns,
                    ]); 
                ?>
            </div>
        </div>
        <?= $this->render('labor-rate-modal', ['laborRatesDataProvider' => $laborRatesDataProvider, 'laborRatesColumns' => $laborRatesColumns]) ?>
    </div>
</div>