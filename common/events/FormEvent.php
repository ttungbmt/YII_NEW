<?php
namespace common\events;

use yii\base\Event;
use yii\base\Model;

class FormEvent extends Event {
    const EVENT_BEFORE_REQUEST = 'beforeUpdate';
    const EVENT_AFTER_REQUEST = 'afterRequest';
    const EVENT_BEFORE_RESEND = 'beforeResend';
    const EVENT_AFTER_RESEND = 'afterResend';

    protected $form;

    public function __construct(Model $form, array $config = [])
    {
        $this->form = $form;
        parent::__construct($config);
    }
    public function getForm()
    {
        return $this->form;
    }
}