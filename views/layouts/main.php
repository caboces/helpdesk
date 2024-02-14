<?php

/** @var \yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use yii\bootstrap5\Html;
use app\widgets\Alert;

AppAsset::register($this);
$this->beginContent('@app/views/layouts/base.php');
?>

<!-- content start -->
<main role="main" class="row">
    <div class="col flex-1 | px-2 py-5 px-lg-5">
        <div class="container-fluid">
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </div>

    <?php echo $this->render('_activity'); ?>
</main>

<?php $this->endContent() ?>