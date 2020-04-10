<?php

namespace ttungbmt\noty;

use kartik\base\AssetBundle;
use yii\web\View;

class NotyAsset extends AssetBundle
{
    public $css = [
        'libs/bower/noty/lib/noty.css'
    ];

    public $js = [
        'libs/bower/noty/lib/noty.min.js'
    ];

}