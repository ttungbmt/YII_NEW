<?php
require __DIR__.'/env.php';

$HOST = $_SERVER['HTTP_HOST'];
$DOMAIN = explode('.', $HOST)[0];
ini_set('memory_limit', '-1');

switch ($DOMAIN){
    case 'pcd-test':
    case 'dichte':
        define('APP_FOLDER', 'pcd');
        break;
    case 'hoakieng':
        define('APP_FOLDER', 'raumau');
        break;
    case 'bodongsongsg':
        define('APP_FOLDER', 'bosongsg');
        break;
    case 'pcd-covid':
    case 'covid':
        define('APP_FOLDER', 'projects/covid');
        break;
    case 'demo':
        define('APP_FOLDER', 'projects/drought');
        break;
    default:
        if($HOST == '113.161.70.126'){
            define('APP_FOLDER', 'bdqh');
        } else {
            define('APP_FOLDER', $DOMAIN);
        }
        break;
}



require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

require __DIR__ . '/../vendor/ttungbmt/yii2-core/src/config/bootstrap.php'; // Overwrite Core
require __DIR__ . '/../common/config/bootstrap.php';

require __DIR__ . '/../'.APP_FOLDER.'/config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../vendor/ttungbmt/yii2-core/src/config/main.php', // Overwrite Core
    require __DIR__ . '/../common/config/main.php',
    require __DIR__ . '/../common/config/main-local.php',
    require __DIR__ . '/../'.APP_FOLDER.'/config/main.php',
    require __DIR__ . '/../'.APP_FOLDER.'/config/main-local.php'
);

Carbon\Carbon::setLocale('vi');

(new yii\web\Application($config))->run();
