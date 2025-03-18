<?php

use app\models\Inventory;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Inventory $model */

$this->title = 'Search Parts';
$this->params['breadcrumbs'][] = ['label' => 'Parts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="parts-search">
    <div class="title-icon d-flex align-items-center">
        <i class="fa-solid fa-gear fa-2xl"></i>
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <p>Search for parts below.</p>
    <?= $this->render('_search', [
        'model' => $model,
        'partTypesOptions' => $partTypesOptions,
        'usersOptions' => $usersOptions,
        'ticketsOptions' => $ticketsOptions,
    ]) ?>
</div>