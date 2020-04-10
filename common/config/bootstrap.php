<?php

$root = dirname(dirname(__DIR__));

date_default_timezone_set('Asia/Ho_Chi_Minh');

Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@common_theme', dirname(__DIR__). '/themes');
Yii::setAlias('@api', $root . '/api');
Yii::setAlias('@console', $root . '/console');


Yii::setAlias('common', dirname(__DIR__));
Yii::setAlias('frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('yeesoft', dirname(dirname(__DIR__)) . '/vendor/yeesoft');

if(is_dir(__DIR__.'/../helpers')){
    //Load all custom function helpers
    foreach(glob(__DIR__.'/../helpers/*.php') as $helper){
        require_once($helper);
    }
}

