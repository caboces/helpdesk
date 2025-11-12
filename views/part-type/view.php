<?php

use app\models\PartType;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\PartType $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Part Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="part-type-view">

    <h1>Part Type: <i><?= Html::encode($this->title) ?></i></h1>

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
            'name',
            'description',
            'created_by' => [
                'attribute' => 'created_by',
                'value' => function (PartType $model) {
                    return "$model->created_by " . Html::a(
                        Html::decode('<i class="fa-solid fa-arrow-up-right-from-square"></i>'),
                        Url::toRoute(['/user/view', 'id' => $model->created_by]), ['target' => '_blank',]);
                },
                'format' => 'raw',
                'enableSorting' => false,
                'filter' => false,
            ],
            'created' => [
                'attribute' => 'created',
                'label' => 'Date Created',
                'value' => function (PartType $model) {
                    return Yii::$app->dateUtils->asDate($model->created);
                }
            ],
            'modified' => [
                'attribute' => 'modified',
                'label' => 'Date Modified',
                'value' => function (PartType $model) {
                    return Yii::$app->dateUtils->asDate($model->modified);
                }
            ],
        ],
    ]) ?>

</div>
