<?php

use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;

/** @var yii\web\View $this */

$this->title = 'Billing Detail Report';
?>
<div class="reports-billing-detail-report">

    <div class="title-icon d-flex align-items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="2rem" height="2rem" fill="currentColor" class="bi bi-bar-chart-line-fill" viewBox="0 0 16 16" aria-hidden="true">
            <path d="M11 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h1V7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7h1z"/>
        </svg>
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="please-work">
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary d-block" data-bs-toggle="modal" data-bs-target="#labor-rates-modal">
            View labor rates
        </button>

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

        <!-- Labor rates modal -->
        <div class="modal fade" id="labor-rates-modal" tabindex="-1" aria-labelledby="labor-rates-modal-label" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="labor-rates-modal-label">Labor Rates</h1>
                    </div>
                    <div class="modal-body">
                    <?php  
                        echo GridView::widget([
                            'dataProvider' => $laborRatesDataProvider,
                            'columns' => $laborRatesColumns,
                        ]);
                    ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>