<?php

use common\libs\activitylog\Log;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use kartik\widgets\Select2;
use yii\web\User;
use yii\web\View;

$config = [
    'modules'    => [
        'datecontrol'   => [
            'class' => '\kartik\datecontrol\Module'
        ],
        'gridview'      => [
            'class' => '\kartik\grid\Module'
        ],
        'treemanager'   => [
            'class' => '\kartik\tree\Module',
            'i18n'  => [
                'class'            => 'yii\i18n\PhpMessageSource',
                'basePath'         => '@common/messages',
                'forceTranslation' => true
            ]
        ],
//        'noty'          => [
//            'class' => 'ttungbmt\noty\Module',
//            'i18n'  => [
//                'class'            => 'yii\i18n\PhpMessageSource',
//                'basePath'         => '@common/messages',
//            ]
//        ],
        'rbac'          => [
            'class'                     => 'johnitvn\rbacplus\Module',
            'userModelClassName'        => null,
            'userModelIdField'          => 'id',
            'userModelLoginField'       => 'username',
            'userModelLoginFieldLabel'  => null,
            'userModelExtraDataColumls' => null,
            'beforeCreateController'    => null,
            'beforeAction'              => null
        ],
        'auth'          => ['class' => 'common\modules\auth\Module'],
        'notifications'          => ['class' => 'common\modules\notifications\Module'],
//        'notifications' => [
//            'class'    => 'webzop\notifications\Module',
//            'channels' => [
//                'screen' => [
//                    'class' => 'webzop\notifications\channels\ScreenChannel',
//                ],
//            ],
//        ],
    ],
    'aliases'    => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@libs'  => '/libs',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
//        'geoserver' => [
//            'class' => \ttungbmt\REST\Geoserver\Yii2\Geoserver::className(),
//            'url' => 'http://localhost:8080/geoserver/',
//            'username' => 'admin',
//            'password' => 'geoserver',
//            'workspace' => 'drought'
//        ],

//        'yee' => [
//            'class' => 'yeesoft\Yee',
//        ],
//        'settings' => [
//            'class' => 'yeesoft\components\Settings'
//        ],
//        'user' => [
//            'class' => 'yeesoft\components\User',
//            'on afterLogin' => function ($event) {
//                \yeesoft\models\UserVisitLog::newVisitor($event->identity->id);
//            }
//        ],
//        'authClientCollection' => [
//            'class' => 'yii\authclient\Collection',
//            'clients' => [
//                'google' => [
//                    'class' => 'yii\authclient\clients\Google',
//                    'clientId' => 'google_client_id',
//                    'clientSecret' => 'google_client_secret',
//                ],
//                'facebook' => [
//                    'class' => 'yii\authclient\clients\Facebook',
//                    'clientId' => 'facebook_client_id',
//                    'clientSecret' => 'facebook_client_secret',
//                ],
//            ],
//        ],
        'moduleUrlRules' => [
            'class'          => '\sheershoff\ModuleUrlRules\ModuleUrlRules',
            'allowedModules' => ['auth'],
        ],
        'api'            => [
            'class'   => 'common\components\Api',
            'dataMap' => [
                dirname(__DIR__) . '/fixtures/danhmuc.php'
            ]
        ],
        'formatter'      => [
            'class'       => 'common\supports\MyFormatter',
            'nullDisplay' => '',
            'dateFormat'  => 'php:d/m/Y',
            'decimalSeparator' => '.'
        ],
        'user'           => [
            'class'                           => 'common\supports\MyUser',
            'on ' . User::EVENT_AFTER_LOGIN   => function ($e) {
                activity(Log::AUTH)->causedBy(user()->identity)->log('Đăng nhập hệ thống');
            },
            'on ' . User::EVENT_AFTER_LOGOUT => function ($e) {
                activity(Log::AUTH)->causedBy(user()->identity)->log('Đăng xuất hệ thống');
                session(['permissions' => [], 'roles' => []]);
            },
        ],
        'authManager'    => [
            'class' => 'common\supports\DbManager',
        ],
        'i18n'           => [
            'translations' => [
                'app*' => [
                    'class'    => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
//                    'fileMap' => [
//                        'app' => 'app.php',
//                        'app/error' => 'error.php',
//                    ],
                ],
                'noty' => [
                    'class'    => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                ],
            ],
        ],

        'errorHandler' => [
            'errorView' => '@common/themes/admin/site/error.php',
            //'exceptionView' => '@yii/views/errorHandler/exception.php'
        ],
        'cache'        => [
            'class' => 'yii\caching\FileCache',
        ],
        'assetManager' => [
            'bundles' => [
                'kartik\select2\Select2Asset'          => ['css' => []],
                'kartik\select2\ThemeBootstrapAsset'   => ['css' => []],
                'kartik\select2\ThemeKrajeeAsset'      => ['css' => []],
//                'softark\duallistbox\DualListboxAsset' => [
//                    'css' => []
//                ],
                'yii\web\JqueryAsset'                  => [
                    'js' => [
//                        '/themes/admin/main/js/core/libraries/jquery.min.js'
                    ]
                ],
                'yii\bootstrap\BootstrapAsset'         => [
                    'css' => [
//                        '/themes/admin/main/css/bootstrap.min.css'
                    ],
                ],
                'yii\bootstrap\BootstrapPluginAsset'   => [
                    'js'        => [
//                        '/themes/admin/main/js/core/libraries/bootstrap.min.js'
                    ],
                    'jsOptions' => ['position' => View::POS_HEAD]
                ],
                'lo\modules\noty\assets\NotyAsset'     => [
                    'animateCss' => false,
                    'buttonsCss' => false,
                    'css'        => [
                    ],
                    'js'         => [
                        '/libs/bower/noty/js/noty/packaged/jquery.noty.packaged.min.js'
                    ]
                ],
            ],
        ],
        'urlManager'   => [
            'class'           => 'yii\web\UrlManager',
            'showScriptName'  => false, // Disable index.php
            'enablePrettyUrl' => true, // Disable r= routes
            'rules'           => [
                'login'           => 'auth/default',
                'logout'          => 'auth/default/logout',
                'register'        => 'auth/default/register',
                'forget-password' => 'auth/default/forget-password',
                'reset-password'  => 'auth/default/reset-password',
                'unlock'          => 'auth/default/unlock',


                'admin' => 'admin/site/index',
            ]
        ],
    ],
    'container'  => [
        'definitions' => [
//            'yii\helpers\Html'                => [
//                'class'  => 'alert alert-danger no-border m-10',
//                'header' => (
//                    '<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>' .
//                    '<p class="text-semibold">Vui lòng sửa các lỗi sau đây: </p>'
//                )
//            ],
            'softark\duallistbox\DualListbox' => [
                'options'       => [
                    'multiple' => true,
                ],
                'clientOptions' => [
                    'moveOnSelect'            => false,
                    'preserveSelectionOnMove' => 'moved',

//                    'nonSelectedListLabel' => 'Non-selected',
//                    'selectedListLabel'    => 'Selected',
//                    'infoText'             => '',
//                    'infoTextFiltered'     => '',
//                    'infoTextEmpty'        => '',
//                    'filterPlaceHolder'    => '',
//                    'filterTextClear'      => '',
                ],
            ],
            'kartik\widgets\Select2'          => [
                'theme'                => Select2::THEME_BOOTSTRAP,
                'language'             => 'vi',
                'defaultOptions'       => [
                    'placeholder' => 'Tất cả',
                ],
                'defaultPluginOptions' => [
                    'allowClear' => true
                ],
            ],

            'kartik\grid\GridView'            => [
                'class'                => 'common\supports\data\GridView',
//                'export'               => [
//                    'fontAwesome' => true,
//                    'showConfirmAlert' => false,
//                ],
//                'toggleDataOptions'    => [
//                ],
                'panelPrefix'          => 'card card-',
//                'panel' => [
//                    'headingOptions' => ['class' => 'card-title'],
//                    'titleOptions' => ['class' => 'card-title'],
//                ],
                'panelFooterTemplate'  => <<< HTML
    <div class="kv-panel-pager d-flex justify-content-between align-items-center m-3">
        {pager}
    </div>
    {footer}
HTML
                ,
                'panelHeadingTemplate' => <<< HTML
    {summary}
    {title}
    <div class="clearfix"></div>
HTML
            ],
            'yii\widgets\Breadcrumbs'         => [
                'encodeLabels'       => false,
                'homeLink'           => [
                    'label' => '<i class="icon-home2 mr-1"></i>',
                    'url'   => '/',
                    'class' => 'breadcrumb-item'
                ],
                'itemTemplate'       => '{link}',
                'activeItemTemplate' => "<span class=\"breadcrumb-item active\">{link}</span>\n",
            ],
            'kartik\export\ExportMenu'        => [
                'target'          => ExportMenu::TARGET_BLANK,
                'fontAwesome'     => true,
                'asDropdown'      => true,
                'dropdownOptions' => [
                    'icon' => '<i class="icon-download10"></i>',
                    'menuOptions' => [
                    ],
                    'style' => 'background-color: white'

                ],
                'showColumnSelector' => false,
                'columnSelectorOptions' => [
                    'icon' => '<i class="icon-cloud-check"></i>',
                    'style' => 'background-color: white'
                ],
                'showConfirmAlert' => false,
                'batchSize'        => 100,
                'onRenderSheet'    => function ($sheet, $widget) {
                    $highestCol = $sheet->getHighestColumn();
                    $highestRow = $sheet->getHighestRow();

                    $letters = collect($widget->columns)
                        ->whereNotIn('hiddenFromExport', true)
                        ->values()
                        ->where('format', 'exceldate')
                        ->keys()
                        ->map(function ($index) {
                            return PHPExcel_Cell::stringFromColumnIndex($index);
                        });

                    foreach ($letters as $letter) {
                        $sheet->getStyle("{$letter}2:{$letter}{$highestRow}")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2); //PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2
                    }
                },
            ],
            'lo\modules\noty\Wrapper'         => [
                'layerClass' => 'common\widgets\Noty',
            ],
            'kartik\widgets\DepDrop'          => [
                'class' => 'common\widgets\DepDrop',
            ],


            'kartik\grid\ActionColumn'        => ['class' => 'common\supports\grid\ActionColumn'],
            'kartik\grid\BooleanColumn'       => ['class' => 'common\supports\grid\BooleanColumn'],
            'kartik\grid\ExpandRowColumn'     => [
                'expandIcon'   => '<span class="icon-circle-right2"></span>',
                'collapseIcon' => '<span class="icon-circle-down2"></span>'
            ],
            'yii\widgets\LinkPager'           => [
//                'class' => \kriss\widgets\LinkPagerWithSubmit::className(),
                'class' => \ttungbmt\pager\LinkPager::className(),
                'template' => '{pageButtons} <div class="ml-2">{customPage} {pageSize}</div>',
                'pageSizeList' => [10, 20, 30, 50, 300],
                'customPageBefore' => ' Trang ',
                'customPageAfter' => ' Hiển thị ',

                'options'                       => ['class' => 'pagination shadow-1'],
                'linkContainerOptions'          => ['class' => 'page-item'],
                'linkOptions'                   => ['class' => 'page-link'],
                'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link']
            ],
            'yii\widgets\ActiveField'         => [
                'errorOptions' => ['class' => 'form-text text-danger']
            ],

        ],
        'singletons'  => [

        ]
    ],
];

if (YII_ENV_DEV) {
//     configuration adjuSMTP ents for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class'  => 'yii\debug\Module',
        //'allowedIPs' => ['127.0.0.1', '::1'],
        'panels' => [
            'httpclient' => [
                'class' => 'yii\\httpclient\\debug\\HttpClientPanel',
            ],
        ],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class'      => 'yii\gii\Module',
        'generators' => [
            'ajaxcrud' => [
                'class'     => 'common\generators\Generator',
                'templates' => [
                    'crud' => '@common/generators/default',
                ]
            ]
        ],
    ];
}

return $config;
