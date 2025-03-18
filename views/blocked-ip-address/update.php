<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\BlockedIpAddress $model */

$this->title = 'Update Blocked Ip Address: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Blocked Ip Addresses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="blocked-ip-address-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
