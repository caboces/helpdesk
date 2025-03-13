<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\PartSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="inventory-search">

    <?php $form = ActiveForm::begin([
        'action' => ['search-results'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <!-- New prop tag (primary key) -->
    <?= $form->field($model, 'new_prop_tag')->label('Asset ID') ?>

    <!-- Funds  -->
    <?= $form->field($model, 'fund_id[]')->widget(Select2::class, [
        'data' => $fundsOptions,
        'options' => ['label' => 'Funds Accounts', 'placeholder' => 'Select funds account(s)'],
        'pluginOptions' => [
            'allowClear' => true,
            'multiple' => true
        ]
    ])->label('Fund(s)') ?>
    
    <!-- Location/building code -->
    <?= $form->field($model, 'bl_code[]')->widget(Select2::class, [
        'data' => $locationsOptions,
        'options' => ['label' => 'Locations', 'placeholder' => 'Select location(s)'],
        'pluginOptions' => [
            'allowClear' => true,
            'multiple' => true
        ]
    ])->label('Location(s)') ?>

    <!-- Delete reason/delete code -->
    <?= $form->field($model, 'delete_code[]')->widget(Select2::class, [
        'data' => $deleteOptions,
        'options' => ['label' => 'Delete Codes', 'placeholder' => 'Select delete code(s)'],
        'pluginOptions' => [
            'allowClear' => true,
            'multiple' => true
        ]
    ])->label('Delete Reason(s)') ?>

    <!-- Inventory class selection -->
    <?= $form->field($model, 'class_id[]')->widget(Select2::class, [
        'data' => $classOptions,
        'options' => ['label' => 'Classes', 'placeholder' => 'Select class(es)'],
        'pluginOptions' => [
            'allowClear' => true,
            'multiple' => true
        ]
    ])->label('Class(es)') ?>

    <!-- Vendors -->
    <?= $form->field($model, 'vendor_id[]')->widget(Select2::class, [
        'data' => $vendorsOptions,
        'options' => ['label' => 'Vendors', 'placeholder' => 'Select vendor(s)'],
        'pluginOptions' => [
            'allowClear' => true,
            'multiple' => true
        ]
    ])->label('Vendor(s)') ?>

    <!-- Tagged -->
    <?= $form->field($model, 'tagged')->radioList([
        '-1' => 'No',
        '0' => 'Yes',
    ])->label('Tagged') ?>

    <!-- Item Description -->
    <?= Html::dropDownList('item_description_order', null, [
        '=' => 'Equal',
        'LIKE' => 'Contains',
    ]) ?>
    <?= $form->field($model, 'item_description')->textInput()->label('Item Description') ?>

    <!-- Serial number -->
    <?= $form->field($model, 'serial_number')->textInput()->label('Serial Number') ?>

    <!-- Purchased date -->
    <?= Html::dropDownList('purchased_date_order', null, [
        '=' => 'On',
        '<' => 'Before',
        '>' => 'After',
    ]) ?>
    <?= $form->field($model, "purchased_date")->input('date', [
        'dateFormat' => 'php:m/d/Y',
    ])->label('Purchased Date') ?>

    <!-- PO Number -->
    <?= $form->field($model, 'po')->textInput()->label('PO Number') ?>

    <!-- Unit price -->
    <?= Html::dropDownList('unit_price_order', 'EQUAL', [
        '=' => 'Equal',
        '>' => 'Greater Than',
        '<' => 'Less Than',
    ]) ?>
    <?= $form->field($model, 'unit_price')->input('number')->label('Unit Price') ?>

    <!-- total price -->
    <?= Html::dropDownList('total_price_order', 'EQUAL', [
        '=' => 'Equal',
        '>' => 'Greater Than',
        '<' => 'Less Than',
    ]) ?>
    <?= $form->field($model, 'total_price')->input('number')->label('Total Price') ?>

    <!-- useful life -->
    <small>Note: I'm not sure what these numbers label, but I'm assuming higher = longer life, lower = shorter life.</small>
    <?= $form->field($model, 'useful_life[]')->widget(Select2::class, [
        'data' => [
            'null' => 'None',
            '2' => '2',
            '3' => '3',
            '5' => '5',
            '10' => '10',
            '15' => '15',
            '20' => '20',
        ],
        'options' => ['label' => 'Useful Lifes', 'placeholder' => 'Select useful life(s)'],
        'pluginOptions' => [
            'allowClear' => true,
            'multiple' => true
        ]
    ])->label('Useful Life(s)') ?>

    <!-- delete status -->
    <?= $form->field($model, 'delete_status[]')->widget(Select2::class, [
        'data' => [
            'F' => 'F',
        ],
        'options' => ['label' => 'Delete Statuses', 'placeholder' => 'Select delete status(es)'],
        'pluginOptions' => [
            'allowClear' => true,
            'multiple' => true
        ]
    ])->label('Delete Status(es)') ?>
    

    <!-- Date deleted -->
    <?= Html::dropDownList('date_deleted_order', 'ON', [
        '=' => 'On',
        '<' => 'Before',
        '>' => 'After',
    ]) ?>
    <?= $form->field($model, "deleted_date")->input('date', [
        'dateFormat' => 'php:m/d/Y',
    ])->label('Date Deleted') ?>

    <!-- Old prop tag -->
    <?= $form->field($model, "old_prop_tag")->textInput()->label('Old Asset ID') ?>

    <!-- Donated to boces -->
    <?= $form->field($model, "donated_to_boces")->radioList([
        '0' => 'Yes',
        '-1' => 'No',
    ])->label('Donated to BOCES') ?>

    <!-- Date donated -->
    <?= Html::dropDownList('donated_date_order', 'ON', [
        '=' => 'On',
        '<' => 'Before',
        '>' => 'After',
    ]) ?>
    <?= $form->field($model, "donated_date")->input('date', [
        'dateFormat' => 'php:m/d/Y',
    ])->label('Donated Date') ?>

    <!-- Created -->
    <?= Html::dropDownList('entry_date_order', 'ON', [
        '=' => 'On',
        '<' => 'Before',
        '>' => 'After',
    ]) ?>
    <?= $form->field($model, "entry_date")->input('date', [
        'dateFormat' => 'php:m/d/Y',
    ])->label('Date Created') ?>

    <!-- Modified -->
    <?= Html::dropDownList('last_modified_date_order', 'ON', [
        '=' => 'On',
        '<' => 'Before',
        '>' => 'After',
    ]) ?>
    <?= $form->field($model, "last_modified_date")->input('date', [
        'dateFormat' => 'php:m/d/Y',
    ])->label('Date Modified') ?>


    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
