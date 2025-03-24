<?php

use app\models\HourlyRate;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\HourlyRate $model */

$this->title = "Hourly Rate: $model->id";
$this->params['breadcrumbs'][] = ['label' => 'Hourly Rates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="hourly-rate-view">

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
            'job_type_id' => [
                'attribute' => 'job_type_id',
                'label' => 'Job Type',
                'value' => function (HourlyRate $model) {
                    return Html::a($model->jobType->name . Html::decode(' <i class="fa-solid fa-arrow-up-right-from-square"></i>'), 
                        Url::toRoute(['/job-type/view', 'id' => $model->job_type_id]), ['target' => '_blank']);
                },
                'format' => 'raw',
                'enableSorting' => false,
                'filter' => false,
            ],
            'rate',
            'summer_rate',
            'description',
            'first_day_effective' => [
                'attribute' => 'first_day_effective',
                'label' => 'First Day Effective',
                'value' => function (HourlyRate $model) {
                    return Yii::$app->dateUtils->asDate($model->first_day_effective);
                }
            ],
            'last_day_effective' => [
                'attribute' => 'last_day_effective',
                'label' => 'Last Day Effective',
                'value' => function (HourlyRate $model) {
                    return Yii::$app->dateUtils->asDate($model->last_day_effective);
                }
            ],
        ],
    ]) ?>

</div>
