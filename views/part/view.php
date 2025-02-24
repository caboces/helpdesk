<?php

use app\assets\AppAsset;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Part $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Parts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
AppAsset::register($this);
?>
<div class="part-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'ticket_id',
            'last_modified_by_user_id',
            'part_number',
            'part_name',
            'quantity',
            'unit_price',
            'pending_delivery',
            'created',
            'modified',
        ],
    ]) ?>

</div>
