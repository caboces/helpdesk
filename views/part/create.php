<?php

use app\assets\AppAsset;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Part $model */
AppAsset::register($this);

$this->title = 'Create Part';
$this->params['breadcrumbs'][] = ['label' => 'Parts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="part-create">

    <div class="title-icon d-flex align-items-center">
        <i class="fa fa-gear fa-2xl"></i>
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <?= $this->render('_form', [
        'models' => $models,
        'ticket_id' => $ticket_id,
        'partTypes' => $partTypes,
    ]) ?>

</div>
