<?php

return [
    // mail
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',

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
    ]
];
