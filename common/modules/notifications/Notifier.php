<?php
namespace common\modules\notifications;

class Notifier extends \tuyakhov\notifications\Notifier
{
    public $channels = [
        'mail' => [
            'class' => '\tuyakhov\notifications\channels\MailChannel',
            'from' => 'ttungbmt@gmail.com'
        ],
        'database' => [
            'class' => '\common\modules\notifications\channels\DatabaseChannel'
        ]
    ];


}