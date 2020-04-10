<?php
namespace common\models;

use common\modules\auth\models\UserInfo;
use pcd\models\HcPhuong;

trait UserTrait
{
    public function getInfo()
    {
        return $this->hasOne(UserInfo::className(), ['user_id' => 'id']);
    }


}