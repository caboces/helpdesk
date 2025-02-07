<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * ticket update asset bundle
 */
class TicketUpdateAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/pills-tab.css',
        'css/forms.css',
        'css/select2.css',
        'css/gridview.css',
    ];
    public $js = [
        'js/ticket.js',
        ['js/ticket-update.js', 'type' => 'module'],
        ['js/dynamic-forms.js', 'type' => 'module'],
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset'
    ];
}
