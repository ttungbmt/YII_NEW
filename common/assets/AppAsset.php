<?php

namespace common\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $jsOptions = ['position' => View::POS_HEAD];

    public $css = [
        'themes/admin/main/css/bootstrap.min.css',
        'themes/admin/main/css/bootstrap_limitless.min.css',
        'themes/admin/main/css/layout.min.css',
        'themes/admin/main/css/components.min.css',
        'themes/admin/main/css/colors.min.css',
        'themes/admin/main/css/extras/animate.min.css',

        'themes/admin/custom/css/theme.css',
    ];

    public $js = [
        'themes/admin/main/js/main/jquery.min.js',
        'themes/admin/main/js/main/bootstrap.bundle.min.js',

        'themes/admin/main/js/plugins/ui/ripple.min.js',
        'themes/admin/main/js/plugins/loaders/pace.min.js',
        'themes/admin/main/js/plugins/loaders/blockui.min.js',
        'themes/admin/main/js/app.js',
        'themes/admin/main/js/plugins/forms/styling/uniform.min.js',

    ];

    public $depends = [
        'common\assets\IconAsset',
    ];
}
