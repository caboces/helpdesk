<?php

use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

NavBar::begin([
    'brandLabel' => Yii::$app->name,
    'brandUrl' => Yii::$app->homeUrl,
    'innerContainerOptions' => ['class' => 'container-fluid'],
    'options' => [
        'class' => 'navbar navbar-expand-lg navbar-dark bg-slate fixed-top',
    ],
]);
$menuItems = [
    [
        'label' => 'DASHBOARD',
        'url' => ['/site/index']
    ],
    [
        'label' => 'TICKETS',
        'url' => ['/ticket/index']
    ],
    [
        'label' => 'INVENTORY',
        'url' => ['/inventory/index']
    ],
    [
        'label' => 'MANAGE',
        'url' => ['/site/manage']
    ],
    [
        'label' => 'REPORTS',
        'url' => ['/reports/index']
    ],
    [
        'label' => 'HELP',
        'url' => ['/site/help']
    ],
];
if (Yii::$app->user->isGuest) {
    $menuItems[] = ['label' => 'LOGIN', 'url' => ['/site/login']];
} else {
    $menuItems[] = [
        'label' => 'LOGOUT (' . Yii::$app->user->identity->username . ')',
        'url' => ['/site/logout'],
        'linkOptions' => [
            'data-method' => 'post',
        ],
    ];
}
echo Nav::widget([
    'options' => ['class' => 'navbar-nav ms-auto'],
    'items' => $menuItems,
]);
NavBar::end();