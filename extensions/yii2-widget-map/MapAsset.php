<?php

namespace ttungbmt\map;

use common\assets\BeginAsset;
use kartik\base\AssetBundle;
use yii\helpers\ArrayHelper;
use yii\web\View;

class MapAsset extends AssetBundle
{
    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public $publishOptions = [
        'forceCopy' => true,
    ];

    public function init()
    {
        $this->setSourcePath(__DIR__ . '/dist');

        $this->css = [
            '/libs/bower/leaflet/dist/leaflet.css',
            'css/main.css',
        ];

        $this->js = [
            '/libs/bower/leaflet/dist/leaflet.js',
            '/libs/node/@turf/turf.min.js',
            '/libs/bower/axios/dist/axios.min.js',

//            'manifest.js',
            'react-ext.js',
            'redux-ext.js',
            'main.js',
        ];

        parent::init();
    }
}