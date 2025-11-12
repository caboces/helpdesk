<?php

namespace app\utils;

use app\models\BlockedIpAddress;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\Request;

class Honeypot {
    /**
     * Tests the honeypot field and blocks the request if filled out.
     * 
     * Throws a "forbidden" exception if the honeypot field was filled.
     */
    public static function test(Request $request, string $location, string $honeypotField = 'vc090h3n') {
        $honeypot = $request->post($honeypotField);
        if ($honeypot) {
            // honeypot was detected, block this IP address
            $blockedIp = new BlockedIpAddress();
            $blockedIp->ip_address = Yii::$app->request->getUserIP();
            $blockedIp->reason = "User submitted nonempty information to honeypot field at $location.";
            $blockedIp->save();
            throw new ForbiddenHttpException("You are forbidden from accessing this resource.");
        }
    }
}

?>