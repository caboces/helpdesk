<?php

use app\models\Part;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\PartSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Parts Management';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="part-index">

    <div class="title-icon d-flex align-items-center">
        <i class="fa-solid fa-gear fa-2xl"></i>
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <p>This is the Parts Management page.</p>
    <p>You cannot create parts outside tickets, but you can update or delete existing parts.</p>
    <!-- action buttons -->
    <div class='container-fluid p-2 | bg-dark shadow-sm'>
        <?= Html::a('Reset filters', ['index'], ['class' => 'btn btn-secondary']); ?>
        <?= Html::a('Detailed Search', ['/part/search'], ['class' => 'btn btn-primary bg-iris border-iris']) ?>
    </div>

    <!-- pill nav -->
    <ul class="nav nav-pills" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pills-active-parts-tab" data-bs-toggle="pill" data-bs-target="#pills-active-parts" type="button" role="tab" aria-controls="pills-active-parts" aria-selected="true">Active Parts</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-inactive-parts-tab" data-bs-toggle="pill" data-bs-target="#pills-inactive-parts" type="button" role="tab" aria-controls="pills-inactive-parts" aria-selected="false">Inactive Parts</button>
        </li>
    </ul>

    <!-- pill content -->
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-active-parts" role="tabpanel" aria-labelledby="pills-active-parts-tab">
            <div class="subsection-info-block">
                <h2>Active Parts</h2>
                <p>Active parts are parts that have all or some of the following properties:</p>
                <ul class="list-group">
                    <li class="list-group-item">The part is associated with an open ticket.</li>
                    <li class="list-group-item">The part is pending delivery.</li>
                </ul>
                <p>Parts that are pending delivery cannot be deleted, assuming they are in transit.</p>
                <div class="table-container container-fluid overflow-x-scroll">
                    <?php Pjax::begin(['id' => 'grid-active-parts']) ?>
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            [
                                'header' => 'Actions',
                                'class' => ActionColumn::class,
                                'urlCreator' => function ($action, Part $model, $key, $index, $column) {
                                    return Url::toRoute([$action, 'id' => $model->id]);
                                }
                            ],
                            'part_name',
                            'po_number',
                            'part_number',
                            'pending_delivery' => [
                                'attribute' => 'pending_delivery',
                                'value' => function(Part $model) {
                                    return $model->pending_delivery == 1 ? 
                                        Html::tag('span', 'Yes ' . Html::decode('<i class="fa-solid fa-truck"></i>'), ['class' => 'text-warning'])
                                        : Html::tag('span', 'No ' . Html::decode('<i class="fa-solid fa-check"></i>'), ['class' => 'text-success']);
                                },
                                'format' => 'raw',
                            ],
                            'last_modified_by_user_id' => [
                                'attribute' => 'last_modified_by_user_id',
                                'label' => 'Last Modified By User',
                                'value' => function (Part $model) {
                                    return Html::a($model->lastModifiedByUser->username . Html::decode(' <i class="fa-solid fa-arrow-up-right-from-square"></i>'), Url::toRoute(['/user/view', 'id' => $model->last_modified_by_user_id]), ['target' => '_blank', 'data-pjax' => 0]);
                                },
                                'format' => 'raw',
                                'enableSorting' => false,
                                'filter' => false,
                            ],
                            'ticket_id' => [
                                'attribute' => 'ticket_id',
                                'label' => 'Ticket',
                                'value' => function (Part $model) {
                                    return Html::a($model->ticket->summary . Html::decode(' <i class="fa-solid fa-arrow-up-right-from-square"></i>'), Url::toRoute(['/ticket/view', 'id' => $model->ticket_id]), ['target' => '_blank', 'data-pjax' => 0]);
                                },
                                'format' => 'raw',
                                'enableSorting' => false,
                                'filter' => false,
                            ],
                        ],
                    ]); ?>
                    <?php Pjax::end() ?>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="pills-inactive-parts" role="tabpanel" aria-labelledby="pills-inactive-parts-tab">
            <div class="subsection-info-block">
                <h2>Inactive Parts</h2>
                <p>Inactive parts are parts that have all of the following properties:</p>
                <ul class="list-group">
                    <li class="list-group-item">The part is not associated with an open ticket.</li>
                    <li class="list-group-item">The part is no longer pending delivery.</li>
                </ul>
                <div class="table-container container-fluid overflow-x-scroll">
                    <?php Pjax::begin(['id' => 'grid-inactive-parts']) ?>
                    <?= GridView::widget([
                        'dataProvider' => $inactivePartsDataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            [
                                'header' => 'Actions',
                                'class' => ActionColumn::class,
                                'urlCreator' => function ($action, Part $model, $key, $index, $column) {
                                    return Url::toRoute([$action, 'id' => $model->id]);
                                }
                            ],
                            'part_name',
                            'po_number',
                            'part_number',
                            'pending_delivery' => [
                                'attribute' => 'pending_delivery',
                                'value' => function(Part $model) {
                                    return $model->pending_delivery == 1 ? 
                                        Html::tag('span', 'Yes ' . Html::decode('<i class="fa-solid fa-truck"></i>'), ['class' => 'text-warning'])
                                        : Html::tag('span', 'No ' . Html::decode('<i class="fa-solid fa-check"></i>'), ['class' => 'text-success']);
                                },
                                'format' => 'raw',
                            ],
                            'last_modified_by_user_id' => [
                                'attribute' => 'last_modified_by_user_id',
                                'label' => 'Last Modified By User',
                                'value' => function (Part $model) {
                                    return Html::a($model->lastModifiedByUser->username . Html::decode(' <i class="fa-solid fa-arrow-up-right-from-square"></i>'), Url::toRoute(['/user/view', 'id' => $model->last_modified_by_user_id]), ['target' => '_blank', 'data-pjax' => 0]);
                                },
                                'format' => 'raw',
                                'enableSorting' => false,
                                'filter' => false,
                            ],
                            'ticket_id' => [
                                'attribute' => 'ticket_id',
                                'label' => 'Ticket',
                                'value' => function (Part $model) {
                                    return Html::a($model->ticket->summary . Html::decode(' <i class="fa-solid fa-arrow-up-right-from-square"></i>'), Url::toRoute(['/ticket/view', 'id' => $model->ticket_id]), ['target' => '_blank', 'data-pjax' => 0]);
                                },
                                'format' => 'raw',
                                'enableSorting' => false,
                                'filter' => false,
                            ],
                        ],
                    ]); ?>
                    <?php Pjax::end() ?>
                </div>
            </div>
        </div>
    </div>
</div>
