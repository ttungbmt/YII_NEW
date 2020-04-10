<?php
namespace ttungbmt\pager;

use kartik\base\AssetBundle;
use yii\web\View;

class LinkPagerAsset extends AssetBundle
{
    public $css = [
    ];

    public $js = [
        'js/link-pager.js'
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . "/assets";
        parent::init();
    }

    public $publishOptions = [
        'forceCopy' => true,
    ];

}