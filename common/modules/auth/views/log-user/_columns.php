<?php
use yii\helpers\Url;
use yii\helpers\Html;

return [
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'causer.username',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'userInfo.fullname',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'description'
    ],
//    [
//        'class'=>'\kartik\grid\DataColumn',
//        'attribute'=>'subject_id',
//    ],
//    [
//        'class'=>'\kartik\grid\DataColumn',
//        'attribute'=>'subject_type',
//    ],
//
//     [
//         'class'=>'\kartik\grid\DataColumn',
//         'attribute'=>'causer_type',
//     ],
     [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'properties',
         'format' => 'raw',
         'value' => function($model){
             $value = json_decode($model->properties);
             if($value)
                return Html::a("Xem chi tiết", [$value->url], ['target' => '_blank', 'data-pjax' => '0']);
         }
     ],
     [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'created_at',
         'label' => 'Thời gian'
     ],
];