<?php

use yii\helpers\Html;
use yii\helpers\Inflector;

?>
<div class="alert alert-danger">
    <h2><?= Html::encode($errorTitle) ?> Errors</h2>
    <hr>
    <?php foreach (Yii::$app->session->getFlash($errorType) as $key => $value): ?>
        <h4><?= Html::encode($errorTitle) ?> for key "<?= Html::encode($key) ?>":</h4>
        <ul>
            <?php foreach($value as $field => $errors): ?>
            <strong><?= Html::encode(Inflector::camel2words($field)) ?></strong>
            <ul>
                <?php foreach($errors as $error): ?>
                    <li><?= Html::encode($error) ?></li>
                <?php endforeach; ?>
            </ul>
            <?php endforeach; ?>
        </ul>
    <?php endforeach; ?>
</div>