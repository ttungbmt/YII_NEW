<?php
namespace common\events;

use yii\base\Event;
use yii\web\UnauthorizedHttpException;

class AccessEvent extends Event {
    const EVENT_CHECK_GUEST = 'checkGuest';

    public function checkGuest($event){
        $sender = $event->sender;
        dd($sender);
//        throw new UnauthorizedHttpException('Bạn không đủ quyền truy cập');
    }
}