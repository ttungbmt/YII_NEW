<?php
namespace common\modules\notifications\widgets;

use common\models\User;
use common\modules\notifications\models\Notification;
use yii\base\Widget;
use yii\web\View;

class Notifications extends Widget
{
    public static $autoIdPrefix = 'notifications-';

    public $pluginName = 'notifications';

    public function init()
    {
        parent::init();
        $this->initOptions();
        $this->registerAssets();
    }

    public function run()
    {
        parent::run();
        $this->renderWidget();
    }

    public function initOptions()
    {

    }

    protected function registerAssets()
    {
        $view = $this->getView();
        $view->registerJs("
            $(function(){
                $('.act-read').click(function(){
                    let {readUrl, goUrl} = $(this).data()
                    $.post(readUrl).done(function(resp){
                        if(resp.status == 'OK' && goUrl){
                            window.location = goUrl
                        }
                    })
                })
            })
        ", View::POS_END);
    }

    protected function renderWidget()
    {
        if(user()->identity) {
            $notifications = user()->identity->unreadNotifications ;
            echo $this->render('@common/modules/notifications/views/default/view', compact('notifications'));
        }
    }
}