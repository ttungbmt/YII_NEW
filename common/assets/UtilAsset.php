<?php

namespace common\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Main backend application asset bundle.
 */
class UtilAsset extends AssetBundle
{
    public $basePath = '@webroot';

    public $baseUrl = '@web';

    public $jsOptions = ['position' => View::POS_HEAD];

    public $js = [
        'libs/bower/lodash/dist/lodash.min.js',
        'libs/bower/urijs/src/URI.min.js',
        'libs/bower/moment/min/moment-with-locales.min.js',
        'libs/bower/jquery-serialize-object/dist/jquery.serialize-object.min.js',
//        'libs/bower/vue/dist/vue.js',
        'https://cdn.jsdelivr.net/npm/vue@2.6.11/dist/vue.js',
        'https://cdn.jsdelivr.net/npm/vuex@3.1.2/dist/vuex.min.js',
    ];
}
