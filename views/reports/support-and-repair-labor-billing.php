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

    <div class="title-icon d-flex align-items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="2rem" height="2rem" fill="currentColor" class="bi bi-bar-chart-line-fill" viewBox="0 0 16 16" aria-hidden="true">
            <path d="M11 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h1V7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7h1z"/>
        </svg>
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="please-work">
        <div>
            <strong>Select a year and month</strong>
            <?= Html::beginForm([Url::to([Yii::$app->controller->getRoute()])], 'GET', [ 'class' => 'year-month-form' ]); ?>
            <?php 
                $currentYear = date('Y');
                $availableYears = []; // past 10 years, in order
                for ($i = 0; $i < 10; $i += 1) {
                    // set the key and value to the same thing. key is the 'value', value is the 'text' or option label
                    $availableYears[$currentYear - $i] = $currentYear - $i;
                }
                $months = [
                    '00' => 'All Months',
                    '01' => 'January', 
                    '02' => 'February', 
                    '03' => 'March', 
                    '04' => 'April', 
                    '05' => 'May', 
                    '06' => 'June', 
                    '07' => 'July', 
                    '08' => 'August', 
                    '09' => 'September', 
                    '10' => 'October', 
                    '11' => 'November', 
                    '12' => 'December'
                ]
            ?>
            <?= Html::label('Year', 'year', ['class' => 'form-label']); ?>
            <?= Html::dropDownList('year', $year, $availableYears, [
                'text' => 'Input a year', 'class' => 'form-control'
            ]); ?>
            <?= Html::label('Month', 'month', ['class' => 'form-label']); ?>
            <?= Html::dropDownList('month', $month, $months, [
                'text' => 'Select a month', 'class' => 'form-control'
            ]); ?>
            <?= Html::label('Job Type', 'jobType', ['class' => 'form-label']); ?>
            <?= Html::dropDownList('jobType', $jobType, $jobTypes, [
                'text' => 'Select a job type', 'class' => 'form-control'
            ]); ?>
            <?= Html::submitButton('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="M11 2H9v3h2z"/>
                        <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
                    </svg>&nbsp;Get Report',
                    ['class' => 'btn btn-primary bg-pacific-cyan border-pacific-cyan text-dark']
            ); ?>
            <?= Html::endForm(); ?>
        </div>
        <p>This report returns the reported tech times for either tech labor or visual production by division, district, department, and building.</p>
        <p><?php
            if (!array_key_exists($month, $months)) {
                echo 'You are viewing an invalid report.';
            } else if (!array_key_exists($jobType, $jobTypes)) {
                dd($jobTypes);
                echo 'You are viewing an invalid report.';
            } else {
                echo Html::tag('span', 'You are viewing the ' . Html::tag('strong', Html::encode($months[$month] . ' ' . $year)) . ' report for ' . Html::tag('strong', Html::encode($jobTypes[$jobType])) . ' work.'); 
            } 
        ?></p>
        <?php
            echo ExportMenu::widget([
                'dataProvider' => $dataProvider,
                'columns' => $gridColumns,
            ]) . "<hr>\n" .
            GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => $gridColumns,
            ]);
        ?>
    </div>
</div>