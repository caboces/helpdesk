<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use yii\bootstrap5\Alert;
use yii\bootstrap5\Breadcrumbs;

\yii\web\YiiAsset::register($this);
AppAsset::register($this);
$this->beginContent('@app/views/layouts/base.php');
?>

<main role="main">
    <div class="container | px-2 py-5 px-lg-5">
        <?=
        Breadcrumbs::widget([
            'homeLink' => [
                'label' => Yii::t('yii', 'Manage'),
                'url' => ['site/manage'],
            ],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ])
        ?>
        <?= $content ?>
    </div>
</main>

<?php $this->endContent() ?>