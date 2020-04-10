<?php
namespace common\supports;

use common\models\MyQuery;
use yii\db\ActiveRecord;

class MyActiveRecord extends ActiveRecord
{
    public static function find()
    {
        return new MyQuery(get_called_class());
    }
}