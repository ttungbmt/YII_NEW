<?php

namespace common\assets;

use yii\web\AssetBundle;

class MaterialAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'themes/admin/main/css/core.min.css',
        'themes/admin/main/css/components.min.css',
        'themes/admin/main/css/colors.min.css',
        'themes/admin/main/css/extras/animate.min.css',

        'themes/admin/custom/css/app.css',
//        'general/scss/app.scss',
    ];

    public $js = [
        'themes/admin/main/js/plugins/loaders/pace.min.js',
        'themes/admin/main/js/plugins/loaders/blockui.min.js',
        'themes/admin/main/js/core/app.js',
        'themes/admin/main/js/plugins/ui/ripple.min.js',
        'themes/admin/main/js/plugins/forms/styling/uniform.min.js',

        'themes/admin/main/js/core/app.js',

        'themes/admin/custom/js/app.js',


//        '/core/libs/bower/dependent-dropdown/js/dependent-dropdown.js',

//        '/core/themes/pcd/assets/dist/index.js',
    ];

    public $depends = [
        'common\assets\IconAssets',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'common\assets\UtilAssets',
    ];
}
