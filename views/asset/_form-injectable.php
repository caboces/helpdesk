<?php

use yii\helpers\Html;

?>
<div id="asset-box" class="dynamic-form asset-container">
    <h3>Add Asset</h3>
    
    <?php foreach ($models as $index => $model): ?>

    <div class="dynamic-form-input-group question-box-no-trim">
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
                <?= $form->field($model, "[$index]new_prop_tag")->textInput() ?>
            </div>
            <div class="col">
                <?php if ($ticket_id): ?>
                    <?= $form->field($model, "[$index]ticket_id", ['inputOptions' => ['value' => $ticket_id]])->textInput(['readonly' => true, 'class' => 'read-only form-control']); ?>
                <?php else: ?>
                    <?= $form->field($model, "[$index]ticket_id")->textInput(); ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="form-group">
            <?= Html::button('Remove', ['class' => 'dynamic-form-button-remove btn btn-outline-secondary border-imperial-red imperial-red btn-skinny']); ?>
            <?= Html::button('Add', ['class' => 'dynamic-form-button-add btn btn-primary bg-iris border-iris btn-skinny']); ?>
        </div>
    </div>

    <?php endforeach; ?>

</div>