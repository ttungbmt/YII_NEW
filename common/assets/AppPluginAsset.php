<?php
namespace common\assets;

use yii\web\AssetBundle;
use yii\web\View;

class AppPluginAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $jsOptions = ['position' => View::POS_END];

    public $css = [
        'libs/bower/perfect-scrollbar/css/perfect-scrollbar.min.css',
        'libs/bower/noty/lib/noty.css',
    ];

    public $js = [
        'themes/admin/main/js/plugins/buttons/spin.min.js',
        'themes/admin/main/js/plugins/buttons/ladda.min.js',
        'libs/bower/noty/lib/noty.min.js',

        'libs/bower/jquery.floatThead/dist/jquery.floatThead.min.js',
        'libs/bower/gasparesganga-jquery-loading-overlay/dist/loadingoverlay.min.js',
        'libs/bower/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js',
        'libs/bower/tableexport.jquery.plugin/tableExport.min.js',
        'themes/admin/custom/js/app.js',

    ];

    public $depends = [
        'common\assets\AppAsset',
        'common\assets\UtilAsset',
        'yii\web\YiiAsset',
    ];
}