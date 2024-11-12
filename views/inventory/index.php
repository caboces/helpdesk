<?php

use app\models\Inventory;
use app\models\LoanedInventory;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
use Symfony\Component\VarDumper\VarDumper;

/** @var yii\web\View $this */
/** @var app\models\InventorySearch $searchModel */
/** @var app\models\LoanedInventorySearch $loanedInvSearchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var yii\data\ActiveDataProvider $loanedInvDataProvider */

$this->title = 'Inventory';
?>
<div class="inventory-index">

    <div class="title-icon d-flex align-items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="2rem" height="2rem" fill="currentColor" class="bi bi-box-seam-fill" viewBox="0 0 16 16" aria-hidden="true">
            <path fill-rule="evenodd" d="M15.528 2.973a.75.75 0 0 1 .472.696v8.662a.75.75 0 0 1-.472.696l-7.25 2.9a.75.75 0 0 1-.557 0l-7.25-2.9A.75.75 0 0 1 0 12.331V3.669a.75.75 0 0 1 .471-.696L7.443.184l.01-.003.268-.108a.75.75 0 0 1 .558 0l.269.108.01.003zM10.404 2 4.25 4.461 1.846 3.5 1 3.839v.4l6.5 2.6v7.922l.5.2.5-.2V6.84l6.5-2.6v-.4l-.846-.339L8 5.961 5.596 5l6.154-2.461z" />
        </svg>
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <!-- action buttons -->
    <div class='container-fluid p-2 | bg-dark shadow-sm'>
        <?= Html::a('Reset filters', ['index'], ['class' => 'btn btn-secondary']); ?>
        <?= Html::a('New loaned inventory', ['createLoanedInventory'], ['class' => 'btn btn-primary bg-purple border-purple text-white']) ?>
        <?= Html::a('Return loaned inventory', ['returnLoanedInventory'], ['class' => 'btn btn-primary bg-purple border-purple text-white']) ?>
    </div>

    <!-- pill nav -->
    <ul class="nav nav-pills" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pills-worked-tab" data-bs-toggle="pill" data-bs-target="#pills-worked" type="button" role="tab" aria-controls="pills-worked" aria-selected="true">Worked equipment</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-loaned-tab" data-bs-toggle="pill" data-bs-target="#pills-loaned" type="button" role="tab" aria-controls="pills-loaned" aria-selected="false">Loaned equipment</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-all-tab" data-bs-toggle="pill" data-bs-target="#pills-all" type="button" role="tab" aria-controls="pills-all" aria-selected="false">All equipment</button>
        </li>
    </ul>

    <!-- pill content -->
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-worked" role="tabpanel" aria-labelledby="pills-worked-tab">
            <div class="subsection-info-block">
                <h2>Worked equipment</h2>
                <p>Every piece of equipment with at least one associated ticket</p>
                <div class="alert alert-warning" role="alert">
                    Filters haven't been made yet! Currently showing all equipment.
                </div>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'tableOptions' => ['class' => 'table table-bordered'],
                    'columns' => [
                        [
                            'class' => ActionColumn::className(),
                            'template' => '{view}',
                            'urlCreator' => function ($action, Inventory $model, $key, $index, $column) {
                                return Url::toRoute([$action, 'new_prop_tag' => $model->new_prop_tag]);
                            }
                        ],
                        'new_prop_tag' => [
                            'attribute' => 'new_prop_tag',
                            'label' => 'Asset Tag',
                        ],
                        'item_description',
                        'serial_number',
                    ],
                ]); ?>
            </div>
        </div>
        <div class="tab-pane fade" id="pills-loaned" role="tabpanel" aria-labelledby="pills-loaned-label">
            <div class="subsection-info-block">
                <h2>Loaned equipment</h2>
                <p>Every piece of equipment borrowed externally</p>
                <?= GridView::widget([
                    'dataProvider' => $loanedInvDataProvider,
                    'filterModel' => $loanedInvSearchModel,
                    'tableOptions' => ['class' => 'table table-bordered'],
                    'columns' => [
                        [
                            'class' => ActionColumn::className(),
                            'template' => '{view}',
                            'urlCreator' => function ($action, LoanedInventory $model, $key, $index, $column) {
                                return Url::toRoute([$action, 'new_prop_tag' => $model->new_prop_tag]);
                            }
                        ],
                        'new_prop_tag' => [
                            'attribute' => 'new_prop_tag',
                            'label' => 'Asset Tag',
                        ],
                        'inventory.item_description',
                        'inventory.serial_number',
                        'borrower_name',
                        'borrower_email',
                        'borrower_phone',
                        'borrower_loc',
                        'borrower_reason',
                        'date_borrowed',
                        'date_returned'
                    ],
                ]); ?>
            </div>
        </div>
        <div class="tab-pane fade" id="pills-all" role="tabpanel" aria-labelledby="pills-all-tab">
            <div class="subsection-info-block">
                <h2>All equipment</h2>
                <p>Every piece of equipment</p>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'tableOptions' => ['class' => 'table table-bordered'],
                    'columns' => [
                        [
                            'class' => ActionColumn::className(),
                            'template' => '{view}',
                            'urlCreator' => function ($action, Inventory $model, $key, $index, $column) {
                                return Url::toRoute([$action, 'new_prop_tag' => $model->new_prop_tag]);
                            }
                        ],
                        'new_prop_tag' => [
                            'attribute' => 'new_prop_tag',
                            'label' => 'Asset Tag',
                        ],
                        'item_description',
                        'serial_number',
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>