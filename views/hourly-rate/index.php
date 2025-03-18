<?php

use app\models\HourlyRate;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Hourly Rates';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hourly-rate-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Hourly Rate', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'job_type_id',
            'rate',
            'summer_rate',
            'description',
            //'first_day_effective',
            //'last_day_effective',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, HourlyRate $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
