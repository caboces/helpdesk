<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\District $model */

$this->title = 'Create District';
$this->params['breadcrumbs'][] = ['label' => 'Districts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="district-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>Use the form below to add a new school district to the Helpdesk application.</p>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
