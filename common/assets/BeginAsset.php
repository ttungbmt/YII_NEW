<?php
namespace common\assets;
use yii\web\AssetBundle;
use yii\web\View;

class BeginAsset extends AssetBundle
{
    public $jsOptions = [
        'position' => View::POS_BEGIN
    ];
}