<?php

return [
    // mail
    'adminEmail' => 'webmaster@caboces.org',
    'supportEmail' => 'helpdesk@caboces.org',
    'senderEmail' => 'noreply@caboces.org',
    'senderName' => 'CABOCES Help Desk',

    // password
    'user.passwordResetTokenExpire' => 3600,
    'user.passwordMinLength' => 8,

    // bootstrap
    'bsVersion' => '5.x',

    'params' => [
        'bsDependencyEnabled' => false, // this will not load Bootstrap CSS and JS for all Krajee extensions
        // you need to ensure you load the Bootstrap CSS/JS manually in your view layout before Krajee CSS/JS assets
        //
        // other params settings below
        // 'bsVersion' => '5.x',
        // 'adminEmail' => 'admin@example.com'
    ],
    'recaptcha' => [
        'apikey' => '6LcEMsEqAAAAAHb_vxGCjgC8iY5Xm2IbUAGDvEpE',
        // 0 is very likely a bot, 1.0 is totally clean.
        'actionThreshold' => 0.5,
    ],
];
