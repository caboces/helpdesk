<?php

use app\models\Feedback;
use kartik\grid\ActionColumn;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Feedback $model */

$this->title = "Feedback From: $model->name";
$this->params['breadcrumbs'][] = ['label' => 'Feedbacks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="feedback-view">
    <div class="title-icon d-flex align-items-center">
        <i class="fa-solid fa-comment fa-2xl"></i>
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    
    <!-- action buttons -->
    <div class='container-fluid p-2 | bg-dark shadow-sm'>
        <?= Html::a('Back', Yii::$app->request->referrer, ['class' => 'btn btn-secondary']); ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], ['class' => 'btn btn-danger', 'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </div>

    <p>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'note',
            'email',
            'phone',
            'ticket_id' => [
                'attribute' => 'ticket_id',
                'label' => 'Ticket',
                'value' => function (Feedback $model) {
                    return Html::a($model->ticket->summary . Html::decode(' <i class="fa-solid fa-arrow-up-right-from-square"></i>'), Url::toRoute(['/ticket/view', 'id' => $model->ticket_id]), ['target' => '_blank', 'data-pjax' => 0]);
                },
                'format' => 'raw',
                'enableSorting' => false,
                'filter' => false,
            ],
            'created' => [
                'attribute' => 'created',
                'label' => 'Date Created',
                'value' => function (Feedback $model) {
                    return Yii::$app->dateUtils->asDate($model->created);
                }
            ],
        ],
    ]) ?>

</div>
