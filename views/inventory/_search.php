<?php

use kartik\select2\Select2;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\PartSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="inventory-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <div class="form-group mb-2">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <div class="row">
        <div class="col-3">
            <!-- New prop tag (primary key) -->
            <?= $form->field($model, 'new_prop_tag')->label('Asset ID') ?>
        </div>
        <div class="col-3">
            <!-- Serial number -->
            <?= $form->field($model, 'serial_number')->textInput()->label('Serial Number') ?>
        </div>
        <div class="col-3">
            <!-- PO Number -->
            <?= $form->field($model, 'po')->textInput()->label('PO Number') ?>
        </div>
        <div class="col-3">
            <!-- Old prop tag -->
            <?= $form->field($model, "old_prop_tag")->textInput()->label('Old Asset ID') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-3">
            <?= Html::label('Item Description Order', 'item_description', ['class' => 'form-label']) ?>
            <?= Html::dropDownList('item_description_order', null, [
                '=' => 'Equal',
                'LIKE' => 'Contains',
            ], ['class' => 'form-control', 'aria-label' => 'Select an ordering for the item description']) ?>
        </div>
        <div class="col">
            <!-- Item Description -->
            <?= $form->field($model, 'item_description')->textInput()->label('Item Description') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <!-- Funds  -->
            <?= $form->field($model, 'fund_id[]')->widget(Select2::class, [
                'data' => $fundsOptions,
                'options' => ['label' => 'Funds Accounts', 'placeholder' => 'Select funds account(s)', 'class' => 'form-select', 'aria-label' => 'Select funds account(s)'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'multiple' => true
                ]
            ])->label('Fund(s)') ?>
        </div>
        <div class="col-6">
            <!-- Location/building code -->
            <?= $form->field($model, 'bl_code[]')->widget(Select2::class, [
                'data' => $locationsOptions,
                'options' => ['label' => 'Locations', 'placeholder' => 'Select location(s)', 'class' => 'form-select', 'aria-label' => 'Select location(s)'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'multiple' => true
                ]
            ])->label('Location(s)') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <!-- Delete reason/delete code -->
            <?= $form->field($model, 'delete_code[]')->widget(Select2::class, [
                'data' => $deleteOptions,
                'options' => ['label' => 'Delete Codes', 'placeholder' => 'Select delete code(s)', 'class' => 'form-select', 'aria-label' => 'Select delete code(s)'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'multiple' => true
                ]
            ])->label('Delete Reason(s)') ?>
        </div>
        <div class="col-6">
            <!-- Inventory class selection -->
            <?= $form->field($model, 'class_id[]')->widget(Select2::class, [
                'data' => $classOptions,
                'options' => ['label' => 'Classes', 'placeholder' => 'Select class(es)', 'class' => 'form-select', 'aria-label' => 'Select class(es)'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'multiple' => true
                ]
            ])->label('Class(es)') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <!-- useful life -->
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
                'options' => ['label' => 'Useful Lifes', 'placeholder' => 'Select useful life(s)', 'class' => 'form-select', 'aria-label' => 'Select useful life(s)'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'multiple' => true
                ]
            ])->label('Useful Life(s)') ?>
        </div>
        <div class="col-6">
            <!-- delete status -->
            <?= $form->field($model, 'delete_status[]')->widget(Select2::class, [
                'data' => [
                    'F' => 'F',
                ],
                'options' => ['label' => 'Delete Statuses', 'placeholder' => 'Select delete status(es)', 'class' => 'form-select', 'aria-label' => 'Select delete status(es)'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'multiple' => true
                ]
            ])->label('Delete Status(es)') ?>
        </div>
    </div>


    <div class="row">
        <div class="col-12">
            <!-- Vendors -->
            <?= $form->field($model, 'vendor_id[]')->widget(Select2::class, [
                'data' => $vendorsOptions,
                'options' => ['label' => 'Vendors', 'placeholder' => 'Select vendor(s)', 'class' => 'form-select', 'aria-label' => 'Select vendor(s)'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'multiple' => true
                ]
            ])->label('Vendor(s)') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-3">
            <?= Html::label('Unit Price Order', 'unit_price', ['class' => 'form-label']) ?>
            <?= Html::dropDownList('unit_price_order', 'EQUAL', [
                '=' => 'Equal',
                '>' => 'Greater Than',
                '<' => 'Less Than',
            ], ['class' => 'form-select', 'aria-label' => 'Select an ordering for the unit price']) ?>
        </div>
        <div class="col">
            <!-- Unit price -->
            <?= $form->field($model, 'unit_price')->input('number')->label('Unit Price ($)') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-3">
            <?= Html::label('Total Price Order', 'total_price_order', ['class' => 'form-label']) ?>
            <?= Html::dropDownList('total_price_order', 'EQUAL', [
                '=' => 'Equal',
                '>' => 'Greater Than',
                '<' => 'Less Than',
            ], ['class' => 'form-select', 'aria-label' => 'Select an ordering for the total price']) ?>
        </div>
        <div class="col">
            <!-- total price -->
            <?= $form->field($model, 'total_price')->input('number')->label('Total Price ($)') ?>
        </div>
    </div>
    
    <!-- Purchased date -->
    <div class="row">
        <div class="col-3">
            <?= Html::label('Purchased Date Order', 'purchased_date_order', ['class' => 'form-label']) ?>
            <?= Html::dropDownList('purchased_date_order', null, [
                '=' => 'On',
                '<' => 'Before',
                '>' => 'After',
            ], ['class' => 'form-control', 'aria-label' => 'Select an ordering for the purchased date']) ?>
        </div>
        <div class="col">
            <?= $form->field($model, "purchased_date")->input('date', [
                'dateFormat' => 'php:m/d/Y',
            ])->label('Purchased Date') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-3">
            <?= Html::label('Date Deleted Order', 'date_deleted_order', ['class' => 'form-label']) ?>
            <?= Html::dropDownList('date_deleted_order', 'ON', [
                '=' => 'On',
                '<' => 'Before',
                '>' => 'After',
            ], ['class' => 'form-select', 'aria-label' => 'Select an ordering for the date deleted']) ?>
        </div>
        <div class="col">
            <!-- Date deleted -->
            <?= $form->field($model, "deleted_date")->input('date', [
                'dateFormat' => 'php:m/d/Y',
            ])->label('Date Deleted') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-2">
            <!-- Tagged -->
            <?= $form->field($model, 'tagged')->radioList([
                '-1' => 'No',
                '0' => 'Yes',
            ])->label('Tagged') ?>
        </div>
        <div class="col-2">
            <!-- Donated to boces -->
            <?= $form->field($model, "donated_to_boces")->radioList([
                '0' => 'Yes',
                '-1' => 'No',
            ])->label('Donated to BOCES') ?>
        </div>
    </div>

    <!-- Date donated -->
    <div class="row">
        <div class="col-3">
            <?= Html::label('Donated Date Order', 'donated_date_order', ['class' => 'form-label']) ?>
            <?= Html::dropDownList('donated_date_order', 'ON', [
                '=' => 'On',
                '<' => 'Before',
                '>' => 'After',
            ], ['class' => 'form-select', 'aria-label' => 'Select an ordering for the donated date']) ?>
        </div>
        <div class="col">
            <?= $form->field($model, "donated_date")->input('date', [
                'dateFormat' => 'php:m/d/Y',
            ])->label('Donated Date') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-3">
            <?= Html::label('Created Date Order', 'entry_date_order', ['class' => 'form-label']) ?>
            <?= Html::dropDownList('entry_date_order', 'ON', [
                '=' => 'On',
                '<' => 'Before',
                '>' => 'After',
            ], ['class' => 'form-select', 'aria-label' => 'Select an ordering for the entry date']) ?>
        </div>
        <div class="col">
            <!-- Created -->
            <?= $form->field($model, "entry_date")->input('date', [
                'dateFormat' => 'php:m/d/Y',
            ])->label('Date Created') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-3">
            <?= Html::label('Last Modified Date Order', 'last_modified_date_order', ['class' => 'form-label']) ?>
            <?= Html::dropDownList('last_modified_date_order', 'ON', [
                '=' => 'On',
                '<' => 'Before',
                '>' => 'After',
            ], ['class' => 'form-select', 'aria-label' => 'Select an ordering for the last modified date']) ?>
        </div>
        <div class="col">
            <!-- Modified -->
            <?= $form->field($model, "last_modified_date")->input('date', [
                'dateFormat' => 'php:m/d/Y',
            ])->label('Date Modified') ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
