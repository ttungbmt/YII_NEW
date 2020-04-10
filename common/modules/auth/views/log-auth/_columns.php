<?php

return [
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'causer.username',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'userInfo.fullname',
    ],
    [
        'attribute' => 'description',
    ],
    [
        'attribute' => 'created_at',
        'label' => 'Thời gian'
    ],
];

