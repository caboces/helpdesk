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
        <svg aria-label="hidden" xmlns="http://www.w3.org/2000/svg" width="2rem" height="2rem" fill="currentColor" class="bi bi-display-fill" viewBox="0 0 16 16">
            <path d="M6 12q0 1-.25 1.5H5a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1h-.75Q10 13 10 12h4c2 0 2-2 2-2V4c0-2-2-2-2-2H2C0 2 0 4 0 4v6c0 2 2 2 2 2z"/>
        </svg>
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <?= $this->render('_form', [
        'models' => $models,
        'ticket_id' => $ticket_id,
    ]) ?>

</div>
