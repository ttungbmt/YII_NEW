<?php

return [
    ['class' => 'yii\grid\SerialColumn'],
    [
        'class' => 'kartik\grid\ActionColumn',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'value' => 'quan.tenquan',
        'attribute' => 'maquan',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'value' => 'phuong.tenphuong',
        'attribute' => 'maphuong',
    ],
    'khupho',
    'to_dp',
];
