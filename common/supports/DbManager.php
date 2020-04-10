<?php
namespace common\supports;

use common\modules\auth\models\AuthItem;

class DbManager extends \yii\rbac\DbManager
{
    public function getRolesByUser($userId = null)
    {
        if(is_null($userId)){
            $userId = \Yii::$app->user->getId();
        }
        return parent::getRolesByUser($userId);
    }

    public function getPermissionsByUser($userId = null)
    {
        if(is_null($userId)){
            $userId = \Yii::$app->user->getId();
        }
        return parent::getPermissionsByUser($userId);
    }

    public function updateRolesUser($userId, $roles)
    {
        $rows = AuthItem::findAll(['name' => $roles]);
        if(empty($rows)) return null;

        auth()->revokeAll($userId);
        foreach ($rows as $item){
            $role = $this->populateItem($item->toArray());
            auth()->assign($role, $userId);
        }
    }
}