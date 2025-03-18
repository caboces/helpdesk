<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\HourlyRate $model */

$this->title = 'Create Hourly Rate';
$this->params['breadcrumbs'][] = ['label' => 'Hourly Rates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hourly-rate-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
