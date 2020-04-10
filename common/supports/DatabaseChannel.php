<?php
namespace common\supports;

use tuyakhov\notifications\channels\ActiveRecordChannel;
use tuyakhov\notifications\NotifiableInterface;
use tuyakhov\notifications\NotificationInterface;

class DatabaseChannel extends ActiveRecordChannel
{
    public $model = 'tuyakhov\notifications\models\Notification';

    public function send(NotifiableInterface $recipient, NotificationInterface $notification)
    {
        /** @var DatabaseMessage $message */
        $message = $notification->exportFor('database');
        list($notifiableType, $notifiableId) = $recipient->routeNotificationFor('database');

        $this->model->setAttributes([
            'level'           => $message->level,
            'subject'         => $message->subject,
            'body'            => $message->body,
            'notifiable_type' => $notifiableType,
            'notifiable_id'   => $notifiableId,
        ]);

        return $this->model->save(true);
    }
}