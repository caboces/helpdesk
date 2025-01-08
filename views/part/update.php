<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Part $model */

$this->title = 'Update Part: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Parts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="part-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'ticket_id' => $ticket_id
    ]) ?>

</div>
