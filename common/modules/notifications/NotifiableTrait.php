<?php
namespace common\modules\notifications;

use common\modules\notifications\models\Notification;
use tuyakhov\notifications\NotifiableTrait as BaseNotifiableTrait;

trait NotifiableTrait
{
    use BaseNotifiableTrait;

    public function getNotifications()
    {
        /** @var $this BaseActiveRecord */
        return $this->hasMany(Notification::className(), ['notifiable_id' => 'id'])
            ->onCondition(['notifiable_type' => get_class($this)]);
    }

    public function getUnreadNotifications()
    {
        return $this->getNotifications()->onCondition(['read_at' => null]);
    }
}