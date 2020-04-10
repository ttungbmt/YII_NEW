<?php 
namespace common\modules\auth\supports;

use app\modules\auth\models\AuthUser;
use Yii;

class AuthService {
    public $manager;
    public $user;
    public $authUser;

    public function __construct()
    {
        $this->manager = Yii::$app->authManager;
        $this->user = Yii::$app->user;
        $this->authUser = $this->user->identity;
    }

    public static function getUserByUsername($username) {
		return AuthUser::find()->where(['username' => $username])->one();
	}

	public function getUser($userID = null) {
        if ($this->user->isGuest) return null;

	    if($userID) return $this->authUser->findOne($userID);

        return $this->authUser;
	}

    public function getAllUsers() {
        $class = $this->user->identityClass;
        $tb = new $class();
        return $tb->find()->all();
    }

    public function getRole() {
        return AuthUser::find()->all();
    }

    public function hasRole($name){
        $roles = $this->manager->getRolesByUser($this->getUser()->id);
        return in_array($name, array_keys($roles));
    }

    public static function hasPermission(){

    }





}
