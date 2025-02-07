<?php

/** @var yii\web\View $this */
/** @var string $content */

use yii\helpers\Html;
use app\assets\AppAsset;
use yii\widgets\Breadcrumbs;

\yii\web\YiiAsset::register($this);
AppAsset::register($this);
$this->beginContent('@app/views/layouts/base.php');
?>

<main role="main">
    <div class="container-fluid | px-2 py-5 px-lg-5">
        <?= $content ?>
    </div>
</main>

<?php $this->endContent() ?>