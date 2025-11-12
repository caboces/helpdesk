<?php

namespace app\utils;

use Yii;

class DateUtils {
    public static function asDate($date) {
        return Yii::$app->formatter->asDate($date, 'php:F j, Y g:i A');
    }
}

?>