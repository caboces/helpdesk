<?php

namespace app\utils;

use app\models\BlockedIpAddress;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;
use yii\web\Request;

    class RequestUtils {
        /**
         * Gets the data behind the request for database entry.
         */
        public static function getFlatData(Request $request): array {
            return [
                'method' => $request->getMethod(),
                'url' => $request->getUrl(),
                'content_type' => $request->getContentType(),
                'ip_address' => $request->getUserIP(),
                'user_agent' => $request->getUserAgent(),
                'cookie' => implode('=', ArrayHelper::map($request->getCookies()->toArray(), 'name', 'value')),
                'referrer' => $request->getReferrer(),
                'body' => $request->getRawBody(),
                'accept_language' => implode(';', $request->getAcceptableLanguages()),
            ];
        }

        public static function isIPBlocked(Request $request) {
            if (BlockedIpAddress::find()->where(['ip_address' => $request->getUserIP()])->exists()) {
                throw new ForbiddenHttpException("You are forbidden from accessing this resource.");
            }
        }
    }
?>