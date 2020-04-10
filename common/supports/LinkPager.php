<?php
/**
 * Created by PhpStorm.
 * User: ttung
 * Date: 4/22/2019
 * Time: 9:59 AM
 */

namespace common\supports;


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
        $js = "
            let id = $('.kv-panel-pager').parents('div[data-pjax-container]').attr('id')
            
        ";
        $view->registerJs($js);
    }
}