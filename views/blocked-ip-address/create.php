<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\BlockedIpAddress $model */

$this->title = 'Create Blocked Ip Address';
$this->params['breadcrumbs'][] = ['label' => 'Blocked Ip Addresses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blocked-ip-address-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
