<?php

namespace common\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class IconAsset extends AssetBundle
{
    public $basePath = '@webroot';

    public $baseUrl = '@web';

    public $css = [
        'libs/bower/roboto-fontface/css/roboto/roboto-fontface.css',

        'themes/admin/main/css/icons/icomoon/styles.css',
        'themes/admin/main/css/icons/fontawesome/styles.min.css',
//        'libs/bower/simple-line-icons/css/simple-line-icons.css',
    ];
}
