<?php

namespace common\supports\grid;

class BooleanColumn extends \kartik\grid\BooleanColumn
{
    public $filterInputOptions = [
        'class' => 'form-control',
        'prompt' => 'Tất cả'
    ];
}