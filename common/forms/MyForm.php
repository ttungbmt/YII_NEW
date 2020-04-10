<?php
namespace common\forms;

use yii\base\DynamicModel;

class MyForm extends DynamicModel {
    public function init() {
        parent::init();

        $this->setDefaultModel();
    }

    public function setDefaultModel(){

    }
}
