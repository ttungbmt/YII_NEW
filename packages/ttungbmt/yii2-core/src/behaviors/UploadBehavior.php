<?php
namespace ttungbmt\behaviors;

use yii\base\Model;
use yii\db\BaseActiveRecord;

class UploadBehavior extends \mohorev\file\UploadBehavior
{
    public $scenarios = [Model::SCENARIO_DEFAULT];

    public $attribute = 'file';

    public function getUploadPath($attr = null, $old = false)
    {
        $attribute = $attr ? $attr : $this->attribute;
        return parent::getUploadPath($attribute, $old);
    }

    public function getUploadUrl($attr = null)
    {
        $attribute = $attr ? $attr : $this->attribute;
        return parent::getUploadUrl($attribute);
    }
}