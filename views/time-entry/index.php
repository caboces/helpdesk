<?php

use app\models\TimeEntry;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\TimeEntrySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Time Entries';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="time-entry-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Time Entry', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'tech_time:datetime',
            'overtime:datetime',
            'travel_time:datetime',
            'itinerate_time:datetime',
            //'entry_date',
            //'user_id',
            //'ticket_id',
            //'created',
            //'modified',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, TimeEntry $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
