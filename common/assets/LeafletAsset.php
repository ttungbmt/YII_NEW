<?php
namespace common\assets;

use yii\web\AssetBundle;
use yii\web\View;

class LeafletAsset extends AssetBundle{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $jsOptions = ['position' => View::POS_HEAD];

    public $css = [
        'https://cdn.jsdelivr.net/npm/leaflet@1.3.4/dist/leaflet.css',
    ];

    public $js = [
        'https://cdn.jsdelivr.net/npm/leaflet@1.3.4/dist/leaflet.js',
        'https://cdn.jsdelivr.net/npm/leaflet-easyprint@2.1.9/dist/bundle.min.js',
        'https://cdn.jsdelivr.net/npm/leaflet-choropleth@1.1.4/dist/choropleth.js',
        'https://cdn.jsdelivr.net/npm/leaflet.minichart@0.2.5/dist/leaflet.minichart.js',
    ];

}