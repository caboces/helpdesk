<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\PartType $model */

$this->title = 'Create Part Type';
$this->params['breadcrumbs'][] = ['label' => 'Part Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="part-type-create">
    <div class="title-icon d-flex align-items-center">
        <i class="fa-solid fa-list fa-2xl"></i>
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <p>Enter the information below to create a new Part Type.</p>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
