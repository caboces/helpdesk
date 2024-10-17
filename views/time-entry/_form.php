<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap5\ActiveForm;
use app\models\TechTicketAssignment;

/** @var yii\web\View $this */
/** @var app\models\TimeEntry $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="time-entry-form">

    <?php $form = ActiveForm::begin([
        'id' => 'add-time-entry-form',
        'enableClientValidation' => true,
        'validateOnSubmit' => true,
    ]); ?>

    <div class="entries-container">
        <div class="alert alert-info p-2" role="alert">
            <em>Please enter times in quarter hour increments (e.g. 0.25 => 15mins; 1.50 => 1hr 30mins; 2.75 => 2hrs 45mins)</em>
        </div>
        <div class="question-box-no-trim">
            <?= $form->field($model, 'last_modified_by_user_id', [
                    'template' => '{input}',
                    'options' => ['tag' => false],
                    'inputOptions' => ['value' => Yii::$app->user->id]
                ])->hiddenInput([
                    'readonly' => true, 
                ])->label(false)
            ?>
            <div class="row">
                <div class="col iris">
                    <?= $form->field($model, 'user_id')
                        ->dropDownList(
                            ArrayHelper::map(TechTicketAssignment::getTechNamesFromTicketId($ticket), 'user_id', 'username'),
                            [
                                'prompt' => 'Select tech',
                            ]
                        ); ?>
                </div>
                <div class="col">
                    <!-- ActiveForm won't take date input... -->
                    <div class="form-group field-timeentry-entry_date required">
                        <label class="control-label" for="timeentry-entry_date">Entry Date</label>
                        <input type="date" id="timeentry-entry_date" class="form-control" name="TimeEntry[entry_date]" placeholder="mm/dd/yyyy" aria-required="true">
                    </div>
                </div>
                <!-- do not allow the techs to change this value, but still submit the ticket id as if it's new input -->
                <div class="col">
                    <?= $form->field($model, 'ticket_id', ['inputOptions' => ['value' => $ticket->id]])->textInput(['readonly' => true, 'class' => 'read-only form-control']) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-3 col-xs-auto">
                    <?= $form->field($model, 'tech_time')->textInput() ?>
                </div>
                <div class="col-3 col-xs-auto">
                    <?= $form->field($model, 'overtime')->textInput() ?>
                </div>
                <div class="col-3 col-xs-auto">
                    <?= $form->field($model, 'travel_time')->textInput() ?>
                </div>
                <div class="col-3 col-xs-auto">
                    <?= $form->field($model, 'itinerate_time')->textInput() ?>
                </div>
            </div>
            <div class="form-group">
                <?= Html::button('Remove', ['class' => 'btn btn-outline-secondary border-imperial-red imperial-red btn-skinny']); ?>
                <?= Html::button('Duplicate', ['class' => 'btn btn-primary bg-iris border-iris btn-skinny']); ?>
            </div>
        </div>
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