<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap5\ActiveForm;
use app\models\TechTicketAssignment;
use yii\jui\DatePicker;

/** @var yii\web\View $this */
/** @var app\models\TimeEntry $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="time-entry-form">

    <?php $form = ActiveForm::begin([
        'id' => 'add-time-entry-form',
        'class' => 'dupe-form',
        'enableClientValidation' => true,
        'validateOnSubmit' => true,
    ]); ?>

    <div class="expanding-input-section entries-container">
        <div class="alert alert-info p-2" role="alert">
            <em>Please enter times in quarter hour increments (e.g. 0.25 => 15mins; 1.50 => 1hr 30mins; 2.75 => 2hrs 45mins)</em>
        </div>
        
        <?php foreach ($models as $index => $model): ?>

        <div class="duplicate-input-group question-box-no-trim">
            <?= $form->field($model, "[$index]last_modified_by_user_id", [
                    'template' => '{input}',
                    'options' => ['tag' => false],
                    'inputOptions' => ['value' => Yii::$app->user->id]
                ])->hiddenInput([
                    'readonly' => true, 
                ])->label(false)
            ?>
            <div class="row">
                <div class="col">
                    <?= $form->field($model, "[$index]user_id")
                        ->dropDownList(
                            ArrayHelper::map(TechTicketAssignment::getTechNamesFromTicketId($ticket_id), 'user_id', 'username'),
                            [
                                'prompt' => 'Select tech',
                            ]
                        ); ?>
                </div>
                <div class="col">
                    <!-- <div class="form-group field-timeentry-entry_date required"> -->
                        <!--<label class="control-label" for="timeentry-entry_date">Entry Date</label> -->
                        <?php //$form->field($model, "[$index]entry_date")->widget(DatePicker::class,
                            //['dateFormat' => 'php:m/d/Y',
                            //    'clientOptions' => [
                            //        'yearRange' => '-1:0',
                            //        'altFormat' => 'php:Y-m-d',
                            //    ]], ['placeholder' => 'mm/dd/yyyy'])->textInput(['placeholder' => Yii::t('app', 'mm/dd/yyyy')]); ?>
                        <!-- <input type="date" id="timeentry-entry_date" class="form-control" name="TimeEntry[{$index}][entry_date]" placeholder="mm/dd/yyyy" aria-required="true" value="<?= date('Y-m-d'); ?>"> -->
                    <!-- </div> -->
                        <?= $form->field($model, "[$index]entry_date")->input('date', [
                            'dateFormat' => 'php:m/d/Y',
                            'value' => date('Y-m-d')
                        ]); ?>
                </div>
                <!-- do not allow the techs to change this value, but still submit the ticket id as if it's new input -->
                <div class="col">
                    <?= $form->field($model, "[$index]ticket_id", ['inputOptions' => ['value' => $ticket_id]])->textInput(['readonly' => true, 'class' => 'read-only form-control']) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-3 col-xs-auto">
                    <?= $form->field($model, "[$index]tech_time")->textInput() ?>
                </div>
                <div class="col-3 col-xs-auto">
                    <?= $form->field($model, "[$index]overtime")->textInput() ?>
                </div>
                <div class="col-3 col-xs-auto">
                    <?= $form->field($model, "[$index]travel_time")->textInput() ?>
                </div>
                <div class="col-3 col-xs-auto">
                    <?= $form->field($model, "[$index]itinerate_time")->textInput() ?>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <?= $form->field($model, "[$index]note")->textInput() ?>
                </div>
            </div>
            <div class="form-group">
                <?= Html::button('Remove', ['class' => 'modal-button-remove btn btn-outline-secondary border-imperial-red imperial-red btn-skinny']); ?>
                <?= Html::button('Add', ['class' => 'modal-button-add btn btn-primary bg-iris border-iris btn-skinny']); ?>
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