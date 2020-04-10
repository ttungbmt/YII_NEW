<?php
namespace common\supports;

use tuyakhov\notifications\models\Notification;
use tuyakhov\notifications\NotifiableTrait;

trait MyNotifiableTrait
{
    use NotifiableTrait;

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