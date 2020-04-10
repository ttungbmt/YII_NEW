<?php
namespace common\components;

class ApplicationEvent extends \yii\base\Behavior
{
    public function events()
    {
        return [
            \yii\web\Application::EVENT_BEFORE_REQUEST => 'onBeforeRequest',
            \yii\web\Application::EVENT_AFTER_REQUEST => 'onAfterRequest',
            \yii\web\Application::EVENT_BEFORE_ACTION => 'onBeforeAction',
            \yii\web\Application::EVENT_AFTER_ACTION => 'onAfterAction',
        ];
    }

    public function onBeforeRequest($event){

    }

    public function onAfterRequest($event){

    }

    public function onBeforeAction($event){
    }

    public function onAfterAction($event){

    }
}