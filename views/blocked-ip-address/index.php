<?php

use app\models\BlockedIpAddress;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\BlockedIpAddressSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Blocked IP Addresses';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blocked-ip-address-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= Html::a('Block an IP ' . Html::decode('<i class="fa-solid fa-ban"></i>'), ['create'], ['class' => 'btn btn-success']) ?>
    <p>View, update, remove, or create blocked IP addresses for the Helpdesk application.</p>
    <p>If the system detects that a bot fills out a form, then they will be blocked from accessing the website.</p>
    <p>Rarely, the system might determine that a real human being was a bot. You will have to remove their entry.</p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'header' => 'Actions',
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, BlockedIpAddress $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
            'ip_address' => [
                'attribute' => 'ip_address',
                'label' => 'IP Address',
                'value' => function (BlockedIpAddress $model) {
                    // NOTE: Using cloudflare because they are secure and probably will exist long term.
                    return Html::a($model->ip_address, "https://radar.cloudflare.com/ip?ip=$model->ip_address", ['target' => '_blank']);
                },
                'format' => 'raw'
            ],
            'reason',
            'created' => [
                'attribute' => 'created',
                'label' => 'Date Created',
                'value' => function (BlockedIpAddress $model) {
                    return Yii::$app->dateUtils->asDate($model->created);
                }
            ],
            'modified' => [
                'attribute' => 'modified',
                'label' => 'Date Modified',
                'value' => function (BlockedIpAddress $model) {
                    return Yii::$app->dateUtils->asDate($model->modified);
                }
            ]
        ],
    ]); ?>


</div>
