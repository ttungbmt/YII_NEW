<?php

namespace common\modules\auth;

/**
 * auth module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'common\modules\auth\controllers';

    public function getUrlRules()
    {
        return require __DIR__.'/routes.php';
    }
}
