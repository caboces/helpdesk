<?php

/** @var \yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use yii\bootstrap5\Html;
use app\widgets\Alert;

AppAsset::register($this);
$this->beginContent('@app/views/layouts/base.php');
\yii\web\YiiAsset::register($this);
?>

<!-- content start -->
<main role="main" class="row bg-login">
    <div class="col flex-1 | p-3 p-xxl-5">
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<?php $this->endContent() ?>