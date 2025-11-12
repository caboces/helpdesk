<?php

use app\models\District;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\District $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Districts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="district-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'description',
            'status',
            'component_district' => [
                'attribute' => 'component_district',
                'label' => 'Component District?',
                'value' => function (District $model) {
                    return $model->component_district === 1 ? 
                        Html::tag('span', 'Yes ' . Html::decode('<i class="fa-solid fa-check"></i>'), ['class' => 'text-success'])
                        : Html::tag('span', 'No ' . Html::decode('<i class="fa-solid fa-xmark"></i>'), ['class' => 'text-danger']);
                },
                'format' => 'raw',
            ],
            'created' => [
                'attribute' => 'created',
                'label' => 'Date Created',
                'value' => function (District $model) {
                    return Yii::$app->dateUtils->asDate($model->created);
                },
            ],
            'modified' => [
                'attribute' => 'modified',
                'label' => 'Date Modified',
                'value' => function (District $model) {
                    return Yii::$app->dateUtils->asDate($model->modified);
                },
            ]
        ],
    ]) ?>

</div>
