<?php

namespace app\utils;

use Yii;

class StringUtils {
    public static function truncateString($input, $maxLength = 50) {
        // Check if the input string is longer than the maximum length
        if (strlen($input) > $maxLength) {
            // Truncate the string and add ellipsis
            return substr($input, 0, $maxLength) . '...';
        }
        
        // Return the original string if it's shorter than or equal to the max length
        return $input;
    }
}

?>