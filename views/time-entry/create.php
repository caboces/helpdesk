<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\TimeEntry $model */

$this->title = 'Create Time Entry';
$this->params['breadcrumbs'][] = ['label' => 'Time Entries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="time-entry-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
