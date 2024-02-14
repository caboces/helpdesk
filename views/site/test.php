<?php

use yii\bootstrap5\Html;
use kartik\select2\Select2;

/** @var yii\web\View $this */

$this->title = 'Select2 Test';
?>
<div class="site-index">
    <!-- Without model and implementing a multiple select -->
    <?php

    echo '<label class="control-label">Provinces</label>';
    echo Select2::widget([
        'name' => 'state_10',
        'data' => $data,
        'options' => [
            'placeholder' => 'Select provinces ...',
            'multiple' => true
        ],
    ]);
    ?>
</div>