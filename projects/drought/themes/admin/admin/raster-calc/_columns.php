<?php

use yii\helpers\Url;
$bands = \drought\models\Gallery::find()->andWhere(['type' => 1])->pluck('code', 'id');

return [
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'code',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'bands',
        'value' => function($model) use($bands){
            return collect(explode(',', $model->bands))->filter()->map(function ($i) use($bands){
                return data_get($bands, $i);
            })->implode(', ');
        }
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'visibleButtons' => [
            'view'   => function ($model) {
                return false;
            },
        ],
        'urlCreator' => function ($action, $model, $key, $index) {
            return url([$action, 'id' => $key]);
        },
        'updateOptions' => ['data-pjax' => 0, 'title' => lang('Update'), 'data-toggle' => 'tooltip'],
    ],

];   