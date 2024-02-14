<?php

use yii\helpers\Html;
?>


<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <?php $this->head() ?>
</head>

<body>
    <?php $this->beginBody() ?>

    <div class="container-fluid d-flex flex-column | h-100">
        <header>
            <?php echo $this->render('_header'); ?>
        </header>
        <?php echo $content ?>
    </div>
    
    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage(); ?>