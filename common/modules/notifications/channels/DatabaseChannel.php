<?php

namespace common\modules\notifications\channels;

use tuyakhov\notifications\channels\ChannelInterface;
use tuyakhov\notifications\messages\DatabaseMessage;
use tuyakhov\notifications\NotifiableInterface;
use tuyakhov\notifications\NotificationInterface;
use yii\base\Component;
use yii\db\ActiveRecordInterface;
use yii\di\Instance;

class DatabaseChannel extends Component implements ChannelInterface
{
    /**
     * @var ActiveRecordInterface|string
     */
    public $model = 'common\modules\notifications\models\Notification';

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->model = Instance::ensure($this->model, 'yii\db\ActiveRecordInterface');
    }

    public function send(NotifiableInterface $recipient, NotificationInterface $notification)
    {
        /** @var DatabaseMessage $message */
        $message = $notification->exportFor('database');
        try {
            list($notifiableType, $notifiableId) = $recipient->routeNotificationFor('database');
            $model = new $this->model;
            $model->setAttributes([
            'level'           => $message->level,
            'subject'         => $message->subject,
            'body'            => $message->body,
            'data'            => $message->data,
            'url'             => $message->url,
            'notifiable_type' => $notifiableType,
            'notifiable_id'   => $notifiableId,
        ]);
//            $this->model->validate();
//            dd($this->model->getErrors());
            return $model->save(true);
        } catch (\Exception $e) {
            dump($e);
        }
    }
}