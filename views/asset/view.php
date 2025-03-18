<?php

use app\models\Asset;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Asset $model */

$this->title = "Update Asset: $model->id";
$this->params['breadcrumbs'][] = ['label' => 'Asset', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="asset-view">
    <h1>Asset ID <?= Html::encode($this->title) ?></h1>
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
            'ticket_id' => [
                'label' => 'Ticket ID',
                'value' => function ($model) {
                    return Html::a($model->ticket_id, "/ticket/view?id=$model->ticket_id", [
                        'target' => '_blank',
                    ]);
                },
                'format' => 'raw',
            ],
            'new_prop_tag',
            'po_number',
            'created' => [
                'attribute' => 'created',
                'label' => 'Date Created',
                'value' => function (Asset $model) {
                    return Yii::$app->dateUtils->asDate($model->created);
                }
            ],
            'modified' => [
                'attribute' => 'modified',
                'label' => 'Date Modified',
                'value' => function (Asset $model) {
                    return Yii::$app->dateUtils->asDate($model->modified);
                }
            ]
        ],
    ]) ?>
</div>
