<?php

namespace drought\forms;

use drought\models\Gallery;
use mohorev\file\UploadBehavior;
use mohorev\file\UploadImageBehavior;
use yii\base\DynamicModel;

class FileManagerForm extends Gallery
{
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
            [['code', 'image', 'date'], 'required'],
            [['code'], 'unique'],
            [['date'], 'date', 'format' => 'php:d/m/Y'],
            ['code', 'match', 'pattern' => '/^[A-Za-z0-9_]\w*$/i'],
            ['image', 'image', 'extensions' => 'jpg, jpeg, gif, png, tif'],
        ];
    }



//    public function behaviors()
//    {
//        return [
//            [
//                'class' => UploadBehavior::className(),
//                'attribute' => 'image',
//                'scenarios' => ['insert', 'update'],
//                'path' => '@webroot/projects/drought/uploads',
//                'url' => '@web/projects/drought/uploads',
//            ],
//        ];
//    }

}