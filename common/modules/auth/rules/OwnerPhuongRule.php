<?php
namespace common\modules\auth\rules;


use app\modules\auth\services\UserService;
use app\modules\role_phuongquan\services\RolePQConst;
use yii\rbac\Rule;

class OwnerPhuongRule extends Rule
{
    public $name = 'isOwnerPhuong';


    /**
     * Executes the rule.
     *
     * @param string|integer $user   the user ID. This should be either an integer or a string representing
     *                               the unique identifier of a user. See [[\yii\web\User::id]].
     * @param Item           $item   the role or permission that this rule is associated with
     * @param array          $params parameters passed to [[CheckAccessInterface::checkAccess()]].
     *
     * @return boolean a value indicating whether the rule permits the auth item it is associated with.
     */
    public function execute($user, $item, $params)
    {
        $userSrv = new UserService($item, $params);
        $roles = collect($userSrv->getRolesByUser($user));
        $model = collect($params)->get('model');
        $user = $userSrv->current();

        if($roles->has(RolePQConst::$CAP_QUAN)){
            return $user->ma_quan == $model->ma_quan;
        } elseif ($roles->has(RolePQConst::$CAP_PHUONG)){
            return $user->ma_phuong == $model->ma_phuong;
        } else {
            return $userSrv->hasAccess($item);
        };
    }

}