<?php
namespace ttungbmt\amcharts;

use kartik\widgets\AssetBundle;

class AmChartsAsset extends AssetBundle
{
    public $basePath = '@webroot';

    public $baseUrl = '@web';

    public $css = [
        'libs/bower/amcharts3/amcharts/plugins/export/export.css'
    ];

    public $js = [
        'libs/bower/amcharts3/amcharts/amcharts.js',
        'libs/bower/amcharts3/amcharts/pie.js',
        'libs/bower/amcharts3/amcharts/serial.js',

        'libs/bower/amcharts3/amcharts/plugins/export/export.js',
        'libs/bower/amcharts3/amcharts/plugins/responsive/responsive.js',
        'libs/bower/amcharts3/amcharts/themes/light.js',
        'libs/custom/amcharts/locales/vi.js',
    ];
}