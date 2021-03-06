<?php

namespace ttungbmt\base;

use kartik\widgets\DatePicker;
use ttungbmt\support\facades\Setting;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Component;
use yii\validators\Validator;

class Bootstrap implements BootstrapInterface
{
//    public function init()
//    {
////        \Yii::configure($this, require __DIR__ . '/config.php');
//        Yii::$container->setDefinitions([
//
//        ]);
//    }

    /**
     * @inheritDoc
     */
    public function bootstrap($app)
    {
        $this->registerModules($app);
        $this->registerComponents($app);
        $this->registerValidators($app);

        $this->setContainers();
    }

    public function setContainers()
    {

        Yii::$container->setDefinitions([
            DatePicker::class => [
                'type' => DatePicker::TYPE_INPUT,
                'options' => [
                    'placeholder' => 'DD/MM/YYYY'
                ],
                'options2' => [
                    'placeholder' => 'DD/MM/YYYDY'
                ],
                'defaultPluginOptions' => [
                    'format' => 'dd/mm/yyyy',
                    'todayBtn' => 'linked',
                    'language' => 'vi',
                    'calendarWeeks' => true,
                    'todayHighlight' => true,
                    'autoclose' => true,
                    'clearBtn' => true,
                ]
            ],
        ]);
    }

    protected function registerModules($app)
    {
        $app->setModules([
            'settings' => [
                'class' => \yii2mod\settings\Module::class,
            ]
        ]);
    }

    protected function registerComponents($app)
    {
        $i18n = data_get($app, 'components.i18n');


        $app->setComponents([
            'http' => [
                'class' => \ttungbmt\components\Http::class
            ],
            'settings' => [
                'class' => \ttungbmt\components\Settings::class,
            ],
            'i18n' => array_merge_recursive($i18n, [
                'translations' => [
                    'yii2mod.settings' => [
                        'class' => 'yii\i18n\PhpMessageSource',
                        'basePath' => '@yii2mod/settings/messages',
                    ],
                ],
            ]),
        ]);
    }

    protected function registerValidators($app)
    {
        Validator::$builtInValidators = array_merge(Validator::$builtInValidators, [
            'geom' => \ttungbmt\validators\GeomValidator::class,
            'atLeast' => \codeonyii\yii2validators\AtLeastValidator::class,
            'dateCompare' => \nepstor\validators\DateTimeCompareValidator::class,
        ]);
    }
}