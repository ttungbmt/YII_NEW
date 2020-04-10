<?php

namespace common\notifications;

use tuyakhov\notifications\messages\AbstractMessage;
use tuyakhov\notifications\NotificationInterface;
use tuyakhov\notifications\NotificationTrait;
use Yii;

/**
 * Created by PhpStorm.
 * User: TUNGPTCN
 * Date: 8/23/2018
 * Time: 12:06 PM
 */
class UserNoty implements NotificationInterface
{
    use NotificationTrait;

    public function exportForDatabase() {
        return Yii::createObject([
            'class' => '\tuyakhov\notifications\messages\DatabaseMessage',
            'subject' => 'Hello',
            'body' => 'Welcome to my awesomesite',
        ]);
    }
}