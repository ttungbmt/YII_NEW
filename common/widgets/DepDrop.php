<?php
/**
 * Created by PhpStorm.
 * User: THANHTUNG
 * Date: 21-Dec-17
 * Time: 4:41 PM
 */

namespace common\widgets;


class DepDrop extends \kartik\widgets\DepDrop
{
    public $defaultPluginOptions = [
//        'placeholder' => 'Chọn...',
        'allowClear' => true,
        'loadingText' => 'Đang tải...',
        'emptyMsg' => 'Không có dữ liệu',
    ];

    public $select2Options = [
        'defaultPluginOptions' => [
            'allowClear' => true
        ],
    ];


    public function init()
    {
        parent::init();
        $val = data_get($this->model, $this->attribute);

        $isInit = (is_null($val) || trim($val) === '') ? false : true;
        $placeholder = data_get($this->options, 'prompt', 'Chọn...');

        $this->pluginOptions = array_merge([
            'ajaxSettings' => ['data' => ['value' => data_get($this->model, $this->attribute)]],
            'placeholder' => $placeholder
        ], (
            $isInit ? ['initialize' => true] : []
        ), $this->pluginOptions);
    }
}