<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/activity.css',
        'css/breadcrumb.css',
        'css/pagination.css',
        'css/login.css',
        'css/pills-tab.css',
        'css/select2.css',
        'css/report.css',
        'css/view.css',
        'css/gridview.css',
        'css/manage.css',
        'css/form.css',
        'css/detailview.css',
    ];
    public $js = [
        'js/app.js',
        // import modules
        ['js/modules/DynamicForm.js', 'type' => 'module'],
        ['js/modules/getUrlParameter.js', 'type' => 'module'],
        ['js/modules/Spinner.js', 'type' => 'module'],
        ['js/modules/switchTabPane.js', 'type' => 'module'],
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset'
    ];
}
