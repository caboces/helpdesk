<?php

use app\models\Inventory;
use app\models\LoanedInventory;
use kartik\grid\GridView as GridGridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\InventorySearch $searchModel */
/** @var app\models\LoanedInventorySearch $loanedInvSearchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var yii\data\ActiveDataProvider $loanedInvDataProvider */

$this->title = 'Inventory Search Results';

?>
<div class="inventory-search-results">

    <div class="title-icon d-flex align-items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="2rem" height="2rem" fill="currentColor" class="bi bi-box-seam-fill" viewBox="0 0 16 16" aria-hidden="true">
            <path fill-rule="evenodd" d="M15.528 2.973a.75.75 0 0 1 .472.696v8.662a.75.75 0 0 1-.472.696l-7.25 2.9a.75.75 0 0 1-.557 0l-7.25-2.9A.75.75 0 0 1 0 12.331V3.669a.75.75 0 0 1 .471-.696L7.443.184l.01-.003.268-.108a.75.75 0 0 1 .558 0l.269.108.01.003zM10.404 2 4.25 4.461 1.846 3.5 1 3.839v.4l6.5 2.6v7.922l.5.2.5-.2V6.84l6.5-2.6v-.4l-.846-.339L8 5.961 5.596 5l6.154-2.461z" />
        </svg>
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <!-- action buttons -->
    <div class='container-fluid p-2 | bg-dark shadow-sm'>
        <?= Html::a('Reset filters', ['index'], ['class' => 'btn btn-secondary']); ?>
        <?= Html::a('New loaned inventory', ['/inventory/create-loaned-inventory'], ['class' => 'btn btn-primary bg-purple border-purple text-white']) ?>
        <?= Html::a('Return loaned inventory', ['/inventory/return-loaned-inventory'], ['class' => 'btn btn-primary bg-purple border-purple text-white']) ?>
        <?= Html::a('Detailed Search', ['/inventory/search'], ['class' => 'btn btn-primary bg-iris border-iris']) ?>
    </div>
    <div>
        <?php if ($count > 0): ?>

        <div class="mt-3 alert alert-success">Found <?= htmlspecialchars($count) ?> related inventory entries.</div>
        <?= Html::a('Back to Search', ['search'], ['class' => 'btn btn-primary']); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'class' => ActionColumn::class,
                    'template' => '{view}',
                    'urlCreator' => function ($action, Inventory $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'new_prop_tag' => $model->new_prop_tag]);
                    }
                ],
                'new_prop_tag' => [
                    'attribute' => 'new_prop_tag',
                    'label' => 'Asset Tag',
                ],
                'po' => [
                    'label' => 'PO Number',
                    'attribute' => 'po',
                ],
                'item_description',
                'serial_number',
            ],
        ]) ?>
        
        <?php else: ?>

        <div class="mt-3 alert alert-danger">Did not find any related inventory entries.</div>
        <?= Html::a('Back to Search', ['search'], ['class' => 'btn btn-primary']); ?>

        <?php endif; ?>
    </div>
</div>