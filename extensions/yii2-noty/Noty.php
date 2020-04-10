<?php
namespace ttungbmt\noty;

use kartik\base\Widget;
use Yii;

class Noty extends Widget
{
    const TYPE_INFO = 'info';
    const TYPE_ERROR = 'error';
    const TYPE_SUCCESS = 'success';
    const TYPE_WARNING = 'warning';

    public $types = [self::TYPE_INFO, self::TYPE_ERROR, self::TYPE_SUCCESS, self::TYPE_WARNING];
    public $showTitle = true;
    public $customTitleDelimiter = '|';

    public $type;
    public $title;
    public $message;


    public $defaultPluginOptions = [
        'theme' => 'sunset',
        'type' => 'success',
        'timeout' => 3000,
        //'closeWith' => ['click', 'button'],
    ];

    public $pluginOptions = [];

    public $pluginName = 'noty';

    public function init()
    {
        parent::init();

        $this->setFlashes();
    }

//    public function run()
//    {
//        parent::run();
////        $this->overrideConfirm();
//    }

    protected function setFlashes(){
        $flashes = session()->getAllFlashes();
        $result = [];

        foreach ($flashes as $type => $data) {
            if (!in_array($type, $this->types)) {
                continue;
            }
            $data = (array)$data;

            foreach ($data as $i => $message) {
                $this->setType($type);
                $this->setTitle();
                $this->setMessage($message);

                $this->initOptions();
            }

            session()->removeFlash($type);
        }
    }

    protected function initOptions()
    {
        $this->pluginOptions = array_merge($this->pluginOptions, [
            'type' => $this->getStyle(),
            'text' => $this->getMessageWithTitle()
        ]);

        $this->registerAssets();
    }

    protected function registerAssets()
    {
        $view = $this->getView();
        NotyAsset::register($view);
        $this->registerPluginOptions($this->pluginName);

        $js = "new Noty({$this->_hashVar}).show();";

        $view->registerJs($js);
    }

    protected function overrideConfirm()
    {
//        if ($this->overrideSystemConfirm) {
//
//            $ok = Yii::t('noty', 'Ok');
//            $cancel = Yii::t('noty', 'Cancel');
//
//            $this->view->registerJs("
//                yii.confirm = function(message, ok, cancel) {
//                    noty({
//                        text: message,
//                        type: 'confirm',
//                        layout: 'center',
//                        modal: true,
//                        buttons: [
//                            {
//                                aConfirmation NeededClass: 'btn btn-primary',
//                                text: '$ok',
//                                onClick: function(res) {
//                                    !ok || ok();
//                                    res.close();
//                                }
//                            },
//                            {
//                                addClass: 'btn btn-danger',
//                                text: '$cancel',
//                                onClick: function(res) {
//                                    !cancel || cancel();
//                                    res.close();
//                                }
//                            }
//                        ]
//                    });
//                }
//            ");
//        }
    }

    public function getStyle(){
        switch ($this->type) {
            case self::TYPE_INFO:
                $style = "information";
                break;
            default:
                $style = $this->type;
        }
        return $style;
    }

    public function getMessageWithTitle()
    {
        $msg = $this->showTitle ? '<b>' . $this->title . '</b><br>' . $this->message : $this->message;
        return $msg;
    }

    public function setType($type)
    {
        $this->type = (in_array($type, $this->types)) ? $type : self::TYPE_INFO;
    }


    public function setTitle()
    {
        switch ($this->type) {
            case self::TYPE_ERROR:
                $t = Yii::t('noty', 'Error');
                break;
            case self::TYPE_INFO:
                $t = Yii::t('noty', 'Info');
                break;
            case self::TYPE_WARNING:
                $t = Yii::t('noty', 'Warning');
                break;
            case self::TYPE_SUCCESS:
                $t = Yii::t('noty', 'Success');
                break;
            default:
                $t = '';
        }

        $this->title = $this->showTitle ? $t : '';
    }

    public function setMessage($message)
    {
        $msg = explode($this->customTitleDelimiter, $message);

        if (isset($msg[1])) {
            $this->message = trim($msg[1]);
            $this->title = trim($msg[0]);
        } else {
            $this->message = $message;
        }
    }
}