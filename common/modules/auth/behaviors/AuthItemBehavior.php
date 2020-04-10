<?php

namespace common\modules\auth\behaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;

class AuthItemBehavior extends Behavior
{

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate',
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
        ];
    }

    public function beforeInsert($event)
    {
        //dd($event->sender);
    }

    public function beforeUpdate($event){
//        dd($event);
    }
}