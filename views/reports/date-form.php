<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>
<!-- Form will GET to whatever the report's URL is -->
<?= Html::beginForm([Url::to([Yii::$app->controller->getRoute()])], 'GET', ['class' => 'date-form']); ?>
<div>
    <strong class="d-block">Select a date range</strong>
    <small class="d-block">The following date form queries from 12:00 AM on the start date to 11:59 PM on the end date.</small>
</div>
<div class="d-flex pb-2">
    <div class="pe-1">
        <?= Html::label('Start Date', 'startDate', ['class' => 'form-label d-block']); ?>
        <?= Html::input('date', 'startDate', $startDate?? date('Y-m-01'), ['placeholder' => 'mm/dd/yyyy', 'class' => 'd-block']) ?>
    </div>
    <div class="ps-1">
        <?= Html::label('End Date', 'endDate', ['class' => 'form-label d-block']); ?>
        <?= Html::input('date', 'endDate', $endDate?? date('Y-m-t', strtotime(date('Y-m-d'))), ['placeholder' => 'mm/dd/yyyy', 'class' => 'd-block']) ?>
    </div>
</div>
<div>
    <?= Html::submitButton('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16" aria-hidden="true">
            <path d="M11 2H9v3h2z"/>
            <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
        </svg>&nbsp;Get Report',
        ['class' => 'd-inline-block btn btn-primary bg-pacific-cyan border-pacific-cyan text-dark']
    ); ?>
</div>
<?= Html::endForm(); ?>