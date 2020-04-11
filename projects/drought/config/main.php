<?php
$params = array_merge(
    require __DIR__ . '/../../../common/config/params.php',
    require __DIR__ . '/../../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);
return [
    'id' => 'app-drought',
    'defaultRoute' => 'maps/index',

    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'drought\controllers',
    'modules' => [

    ],

    'components' => [
        'api' => [
            'class' => 'common\components\Api',
            'dataMap' => [
                dirname(__DIR__).'/fixtures/danhmuc.php',
            ]
        ],
        'view' => [
            'theme' => [
                'basePath' => '@app/themes/admin',
                'baseUrl' => '@web/drought',
                'pathMap' => [
                    '@app/views' => [
                        '@app/themes/admin',
                        '@common/themes/admin',
                    ],
                ],
            ],
        ],
        'i18n' => [
            'translations' => [
                'drought*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                ],
            ],
        ],

        'user' => [
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'request' => [
            'csrfParam' => '_csrf-drought',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'session' => [
            // this is the name of the session cookie used for login on the drought
            'name' => 'advanced-drought',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager'   => [
            'class'           => 'yii\web\UrlManager',
            'showScriptName'  => false, // Disable index.php
            'enablePrettyUrl' => true, // Disable r= routes
            'rules'           => [

            ]
        ],
    ],
    'params' => $params,

    'as beforeRequest' => [
        'class' => 'yii\filters\AccessControl',
        'only' => ['admin/*'],
        'except' => [
            //'admin/*'
        ],
        'rules' => [
            [
                'allow' => true,
                'roles' => ['@']
            ],
        ],
    ],
];


