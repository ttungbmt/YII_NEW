<?php
namespace ttungbmt\pager;

use kartik\base\Widget;
use Yii;
use yii\helpers\Json;

class LinkPager extends \liyunfang\pager\LinkPager
{
    public function run()
    {
        parent::run();
        $view = $this->getView();

    }

    public function init()
    {
        parent::init();
        $this->registerAssets();
    }

    public function registerAssets()
    {
        $view = $this->getView();

        LinkPagerAsset::register($view);
        $pagination = $this->pagination;
        $jsOptions = [
            // Current url
            'url' => $this->pagination->createUrl($pagination->getPage())
        ];

        $js = "$(function () {pagerWidget.init(" . Json::encode($jsOptions) . ");})";
        $view->registerJs($js);
    }
}