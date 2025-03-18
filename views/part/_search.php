<?php

use kartik\select2\Select2;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\PartSearch $model */
/** @var yii\bootstrap5\ActiveForm $form */
?>

<div class="part-search">

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
            <!-- part id (primary key) -->
            <?= $form->field($model, 'id')->label('Part ID') ?>
        </div>
        <div class="col-3">
            <!-- part number -->
            <?= $form->field($model, 'part_number')->textInput()->label('Part Number') ?>
        </div>
        <div class="col">
            <!-- po number -->
            <?= $form->field($model, 'po_number')->textInput()->label('PO Number') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-4">
            <!-- Part type -->
            <?= $form->field($model, 'part_type_id[]')->widget(Select2::class, [
                'data' => $partTypesOptions,
                'options' => ['label' => 'Part Type(s)', 'placeholder' => 'Select part type(s)', 'class' => 'form-select', 'aria-label' => 'Select part type(s)'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'multiple' => true
                ]
            ])->label('Part Type(s)') ?>
        </div>
        <div class="col-4">
            <!-- ticket id -->
            <?= $form->field($model, 'ticket_id[]')->widget(Select2::class, [
                'data' => $ticketsOptions,
                'options' => ['label' => 'Ticket', 'placeholder' => 'Select ticket(s)', 'class' => 'form-select', 'aria-label' => 'Select ticket(s)'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'multiple' => true
                ]
            ])->label('Ticket(s)') ?>
        </div>
        <div class="col-4">
            <!-- last modified by user -->
            <?= $form->field($model, 'last_modified_by_user_id[]')->widget(Select2::class, [
                'data' => $usersOptions,
                'options' => ['label' => 'Last Modified By', 'placeholder' => 'Select user(s)', 'class' => 'form-select', 'aria-label' => 'Select user(s)'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'multiple' => true
                ]
            ])->label('Last Modified By User') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-3">
            <?= Html::label('Part Name Order', 'part_name_order', ['class' => 'form-label']) ?>
            <?= Html::dropDownList('part_name_order', null, [
                '=' => 'Equal',
                'LIKE' => 'Contains',
            ], ['aria-label' => 'Select an ordering for the part name', 'class' => 'form-select']) ?>
        </div>
        <div class="col">
            <!-- part name -->
            <?= $form->field($model, 'part_name')->textInput()->label('Part Name') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-3">
            <?= Html::label('Note Order', 'note_order', ['class' => 'form-label']) ?>
            <?= Html::dropDownList('note_order', null, [
                '=' => 'Equal',
                'LIKE' => 'Contains',
            ], ['aria-label' => 'Select an ordering for the note', 'class' => 'form-select']) ?>
        </div>
        <div class="col">
            <!-- Item Note -->
            <?= $form->field($model, 'note')->textInput()->label('Note') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-3">
            <?= Html::label('Quantity Order', 'quantity_order', ['class' => 'form-label']) ?>
            <?= Html::dropDownList('quantity_order', null, [
                '=' => 'Equal',
                '>' => 'Greater Than',
                '<' => 'Less Than',
            ], ['aria-label' => 'Select an ordering for the quantity', 'class' => 'form-select']) ?>
        </div>
        <div class="col">
            <!-- quantity -->
            <?= $form->field($model, 'quantity')->input('number')->label('Quantity') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-3">
            <?= Html::label('Unit Price Order', 'unit_price_order', ['class' => 'form-label']) ?>
            <?= Html::dropDownList('unit_price_order', null, [
                '=' => 'Equal',
                '>' => 'Greater Than',
                '<' => 'Less Than',
            ], ['aria-label' => 'Select an ordering for the unit price', 'class' => 'form-select']) ?>
        </div>
        <div class="col">
            <!-- Unit price -->
            <?= $form->field($model, 'unit_price')->input('number')->label('Unit Price ($)') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-3">
            <?= Html::label('Created Date Order', 'created_date_order', ['class' => 'form-label']) ?>
            <?= Html::dropDownList('created_date_order', null, [
                '=' => 'On',
                '<' => 'Before',
                '>' => 'After',
            ], ['aria-label' => 'Select an ordering for the created date', 'class' => 'form-select']) ?>
        </div>
        <div class="col">
            <!-- Created -->
            <?= $form->field($model, "created")->input('date', [
                'dateFormat' => 'php:m/d/Y',
            ])->label('Date Created') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-3">
            <?= Html::label('Modified Date Order', 'modified_order', ['class' => 'form-label']) ?>
            <?= Html::dropDownList('modified_order', null, [
                '=' => 'On',
                '<' => 'Before',
                '>' => 'After',
            ], ['aria-label' => 'Select an ordering for the modified date', 'class' => 'form-select']) ?>
        </div>
        <div class="col">
            <!-- Modified -->
            <?= $form->field($model, "modified")->input('date', [
                'dateFormat' => 'php:m/d/Y',
            ])->label('Date Modified') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-2">
            <!-- pending delivery -->
            <?= $form->field($model, 'pending_delivery')->radioList([
                '0' => 'No',
                '1' => 'Yes',
            ])->label('Pending Delivery') ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
