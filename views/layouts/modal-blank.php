<?php

/** @var yii\web\View $this */
/** @var string $content */

use yii\helpers\Html;
use app\assets\AppAsset;
use yii\widgets\Breadcrumbs;

AppAsset::register($this);
$this->beginContent('@app/views/layouts/base.php');
?>

<main role="main">
    <div class="container-fluid | p-2">
        <?= $content ?>
    </div>
</main>

<?php $this->endContent() ?>