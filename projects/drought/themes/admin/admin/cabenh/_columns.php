<?php
$dm_phanloai = api('dm_phanloai');

return [
    ['class' => 'yii\grid\SerialColumn'],
    ['class' => 'kartik\grid\ActionColumn',],
    'hoten',
    'ma_qg',
    'ma_hcm',
    'diachi:ntext',
    [
        'class' => '\kartik\grid\DataColumn',
        'value' => 'quan.tenquan',
        'attribute' => 'maquan',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'maphuong',
        'value' => 'phuong.tenphuong',
    ],
    'tuoi',
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'phanloai',
        'value' => function($model) use($dm_phanloai){
            return data_get($dm_phanloai, !is_null($model->phanloai) ? $model->phanloai : '');
        },
    ],
];