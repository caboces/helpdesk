<?php

use app\models\TicketDraft;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Ticket Drafts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-draft-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Ticket Draft', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'requestor',
            'division_id',
            'department_id',
            'department_building_id',
            //'district_id',
            //'district_building_id',
            //'location',
            //'job_type_id',
            //'job_category_id',
            //'summary',
            //'description',
            //'email:email',
            //'phone',
            //'frozen',
            //'ip_address',
            //'accept_language',
            //'user_agent',
            //'created',
            //'modified',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, TicketDraft $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
