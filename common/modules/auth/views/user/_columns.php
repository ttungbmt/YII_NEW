<?php

use kartik\grid\GridView;
use kartik\helpers\Html;
use kartik\widgets\DepDrop;
use yii\helpers\Url;

$dm_quan = api('/dm/quan');
$url_phuong = url(['/api/dm/phuong']);

return [
    [
        'class' => 'kartik\grid\SerialColumn',
    ],
    [
        'attribute' => 'email',
    ],
    [
        'attribute' => 'username',
    ],
    [
        'attribute'           => 'maquan',
        'value'               => 'info.hcQuan.tenquan',
        'label'               => 'Quận huyện',
        'filterType'          => GridView::FILTER_SELECT2,
        'filter'              => $dm_quan,
        'filterWidgetOptions' => ['pluginOptions' => ['allowClear' => true],],
        'filterInputOptions'  => ['id' => 'drop-quan', 'placeholder' => 'Tất cả'],
        'format'              => 'raw'
    ],
    [
        'attribute'           => 'maphuong',
        'value'               => 'info.hcPhuong.tenphuong',
        'label'               => 'Phường xã',
//        'filterType'          => '\kartik\widgets\DepDrop',
        'filter'              => [],
//        'filterWidgetOptions' => [
//            'type' => DepDrop::TYPE_SELECT2,
//            'select2Options' => ['pluginOptions' => ['allowClear' => true]],
//            'pluginOptions' => [
//                'initialize' => true,
//                'depends' => ['drop-quan'],
//                'url' => $url_phuong
//            ]],
//        'filterInputOptions'  => ['placeholder' => 'Tất cả'],
    ],
    [
        'class'     => 'kartik\grid\BooleanColumn',
        'attribute' => 'status',
    ],
//    [
//        'attribute' => 'created_at',
//        'format'    => ['date', 'php:d/m/Y']
//    ],
    [
        'class'         => 'kartik\grid\ActionColumn',
        'template'      => '{login}{view}{update}{delete}',
        'urlCreator'    => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'viewOptions'   => ['role' => 'modal-remote', 'title' => trans('app', 'View'), 'data-toggle' => 'tooltip'],
        'updateOptions' => ['title' => trans('app', 'Update'), 'data-toggle' => 'tooltip'],
        'buttons'       => [
            'login' => function ($url, $model, $key) {
                return Html::a('<i class="icon-users2"></i>', ['/auth/user/login-as-user', 'id' => $model->id], ['data-toggle' => 'tooltip', 'title' => 'Đăng nhập với tư cách user', 'data-pjax' => 0]);
            }
        ],
    ],

];   