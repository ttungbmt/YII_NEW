<?php

namespace common\forms;

use yii\base\DynamicModel;

class ImportForm extends DynamicModel
{
    const SCENARIO = 'import';

    protected $_attributes = [];
    protected $_dynamicFormAttrs = [];

    public function attributeLabels(){
        return [
        ];
    }

    public function __construct(array $attributes = [], $config = [])
    {
        foreach ($attributes as $name => $value) {
            $this->_attributes[$name] = $value;
        }
        parent::__construct($config);
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->_attributes)) {
            return $this->_attributes[$name];
        }

        return parent::__get($name);
    }


    public function attributes()
    {
        return $this->_attributes;
    }

    public function formAttrs()
    {
        foreach ($this->_attributes as $k => $value) {
            $this->_dynamicFormAttrs += [$k => ['label' => $k]];
        }
        return $this->_dynamicFormAttrs;
    }
}