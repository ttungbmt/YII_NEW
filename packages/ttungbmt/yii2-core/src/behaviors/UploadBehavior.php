<?php
namespace ttungbmt\behaviors;

use Imagine\Image\ManipulatorInterface;
use yii\base\Model;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;
use yii\imagine\Image;
use yii\web\UploadedFile;
use function _\internal\parent;

class UploadImageBehavior extends \mohorev\file\UploadImageBehavior
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

    protected function generateImageThumb($config, $path, $thumbPath)
    {
        $file = UploadedFile::getInstance($this->owner, $this->attribute);
        if(data_get($file, 'extension') === 'tif'){

        } else {
            parent::generateImageThumb($config, $path, $thumbPath);
        }

    }

    protected function getExtension(){

    }
}