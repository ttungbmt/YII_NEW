<?php
namespace common\forms;

use common\models\MyModel;
use yii\web\UploadedFile;

class UploadForm extends MyModel
{
    public $file;
    public $date_format;

    public function rules()
    {
        return [
            [['file'], 'file',
                'extensions' => 'csv, xlsx, xls',
                'checkExtensionByMimeType' => false],
            [['file'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'file' => 'File tải lên',
        ];
    }

    public function validateForm(){
        $this->file = UploadedFile::getInstance($this, 'file');

        return $this->validate();
    }
}