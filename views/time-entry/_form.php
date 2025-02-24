<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap5\ActiveForm;
use app\models\TechTicketAssignment;

/** @var yii\web\View $this */
/** @var app\models\TimeEntry $model */
/** @var yii\bootstrap5\ActiveForm $form */
?>

<div class="time-entry-form">

    <?php $form = ActiveForm::begin([
        'id' => 'add-time-entry-form',
        'class' => 'dupe-form',
        'enableClientValidation' => true,
        'validateOnSubmit' => true,
    ]); ?>

    <div class="dynamic-form entries-container">
        <div class="alert alert-info p-2" role="alert">
            <em>Please enter times in quarter hour increments (e.g. 0.25 => 15mins; 1.50 => 1hr 30mins; 2.75 => 2hrs 45mins)</em>
        </div>
        
        <?php foreach ($models as $index => $model): ?>

        <div class="dynamic-form-input-group question-box-no-trim">
            <?= $form->field($model, "[$index]last_modified_by_user_id", [
                    'template' => '{input}',
                    'options' => ['tag' => false],
                    'inputOptions' => ['value' => Yii::$app->user->id]
                ])->hiddenInput([
                    'readonly' => true, 
                    'class' => 'dynamic-form-clone-value',
                ])->label(false)
            ?>
            <div class="row">
                <div class="col">
                    <?= $form->field($model, "[$index]user_id")
                        ->dropDownList(
                            ArrayHelper::map(TechTicketAssignment::getTechNamesFromTicketId($ticket_id), 'user_id', 'username'), [
                                'prompt' => 'Select tech',
                            ]
                        ); ?>
                </div>
                <div class="col">
                    <?= $form->field($model, "[$index]entry_date")->input('date', [
                        'dateFormat' => 'php:m/d/Y',
                        'value' => date('Y-m-d')
                    ]); ?>
                </div>
                <!-- do not allow the techs to change this value, but still submit the ticket id as if it's new input -->
                <div class="col">
                    <?= $form->field($model, "[$index]ticket_id", ['inputOptions' => ['value' => $ticket_id]])->textInput(['readonly' => true, 'class' => 'read-only form-control dynamic-form-clone-value']) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-3 col-xs-auto">
                    <?= $form->field($model, "[$index]tech_time")->textInput(['class' => 'form-control dynamic-form-clone-value']) ?>
                </div>
                <div class="col-3 col-xs-auto">
                    <?= $form->field($model, "[$index]overtime")->textInput(['class' => 'form-control dynamic-form-clone-value']) ?>
                </div>
                <div class="col-3 col-xs-auto">
                    <?= $form->field($model, "[$index]travel_time")->textInput(['class' => 'form-control dynamic-form-clone-value']) ?>
                </div>
                <div class="col-3 col-xs-auto">
                    <?= $form->field($model, "[$index]itinerate_time")->textInput(['class' => 'form-control dynamic-form-clone-value']) ?>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <?= $form->field($model, "[$index]note")->textInput() ?>
                </div>
            </div>
            <div class="form-group">
                <?= Html::button('Remove', ['class' => 'dynamic-form-button-remove btn btn-outline-secondary border-imperial-red imperial-red btn-skinny']); ?>
                <?= Html::button('Add', ['class' => 'dynamic-form-button-add btn btn-primary bg-iris border-iris btn-skinny']); ?>
            </div>
        </div>

        <?php endforeach; ?>

    </div>

    <div class="form-group">
        <?= Html::submitButton('Confirm new time entry', [
            'id' => 'confirm-time-entry',
            'class' => 'mt-4 btn btn-primary bg-pacific-cyan border-pacific-cyan',
            'form' => 'add-time-entry-form',
        ]); ?>
    </div>

    <?php ActiveForm::end()?>

</div>