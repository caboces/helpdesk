<?php

use app\models\Feedback;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Feedbacks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="feedback-index">
    <div class="title-icon d-flex align-items-center">
        <i class="fa-solid fa-comment fa-2xl"></i>
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <!-- action buttons -->
    <div class='container-fluid p-2 | bg-dark shadow-sm'>
        <?= Html::a('Back', ['site/manage'], ['class' => 'btn btn-secondary']); ?>
        <?= Html::a(Html::decode('<i class="fa-solid fa-plus"></i>') . ' Create', ['create'], ['class' => 'btn btn-primary bg-pacific-cyan border-pacific-cyan text-dark']) ?>
    </div>

    <!-- pill nav -->
    <ul class="nav nav-pills" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pills-recent-feedback-tab" data-bs-toggle="pill" data-bs-target="#pills-recent-feedback" type="button" role="tab" aria-controls="pills-recent-feedback" aria-selected="true">Recent Feedback</button>
        </li>
        <!-- <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-inactive-tab" data-bs-toggle="pill" data-bs-target="#pills-inactive" type="button" role="tab" aria-controls="pills-inactive" aria-selected="false">Inactive</button>
        </li> -->
    </ul>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => ActionColumn::class,
                'template' => '{view} {delete}',
                'urlCreator' => function ($action, Feedback $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
            'name',
            'note:ntext',
            'email:email',
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
    ]); ?>


</div>
