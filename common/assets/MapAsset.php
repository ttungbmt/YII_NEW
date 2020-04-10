<?php

namespace common\assets;

use yii\web\AssetBundle;
use yii\web\View;

class MapAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $jsOptions = ['position' => View::POS_END];

    public $css = [
        'libs/bower/roboto-fontface/css/roboto/roboto-fontface.css',
        'themes/admin/main/css/core.min.css',
        'themes/admin/main/css/components.min.css',
        'themes/admin/main/css/colors.min.css',
        'themes/admin/main/css/extras/animate.min.css',

//        'libs/bower/PACE/themes/orange/pace-theme-flash.css',
        'libs/bower/jspanel3/source/jquery.jspanel.min.css',
        'libs/bower/SpinKit/css/spinkit.css',
        'libs/bower/perfect-scrollbar/css/perfect-scrollbar.min.css',
        'libs/bower/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',

        'libs/custom/jquery.layout/layout-default-latest.css',
        'libs/map/dist/leaflet-plugins.css',
        'libs/map/custom/leaflet.icon.wave/src/L.Icon.Wave.css',
        'libs/map/custom/leaflet.legend/L.Control.Legend.css',

        'pcd/map/assets/scss/app.css',
    ];

    public $js = [
        'https://maps.googleapis.com/maps/api/js?key=AIzaSyBDKq9HHFQReMFM7PJi3ibNq30WzH8unkI&libraries=drawing,geometry,places',


        'libs/bower/eventEmitter/EventEmitter.min.js',
        'libs/bower/jquery-ui/jquery-ui.min.js',
//        'libs/bower/PACE/pace.min.js',
        'libs/bower/jspanel3/source/jquery.jspanel-compiled.min.js',
        'libs/bower/hammerjs/hammer.min.js',
        'libs/bower/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
        'libs/bower/bootstrap-datepicker/dist/locales/bootstrap-datepicker.vi.min.js',
        'libs/bower/handlebars/handlebars.min.js',
        'libs/bower/chroma-js/chroma.min.js',

        'libs/bower/amcharts3/amcharts/amcharts.js',
        'libs/bower/amcharts3/amcharts/serial.js',

        'libs/bower/axios/dist/axios.min.js',
        'libs/bower/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js',

        'libs/custom/jquery.layout/jquery.layout-latest.js',
        'libs/map/dist/leaflet-plugins.js',
        'libs/map/core/leaflet.wms/leaflet.wms.js',
        'libs/map/custom/leaflet.icon.wave/src/L.Icon.Wave.js',
        'libs/map/custom/leaflet.legend/L.Control.Legend.js',

        'pcd/map/bundle.js',
    ];

    public $depends = [
        'common\assets\IconAssets',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'common\assets\UtilAssets',
    ];
}
