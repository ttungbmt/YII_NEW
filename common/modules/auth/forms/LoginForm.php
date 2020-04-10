<?php

namespace common\modules\auth\forms;

use common\models\MyModel;
use common\models\User;
use Yii;

class LoginForm extends MyModel
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }


    public function attributeLabels()
    {
        return [
            'username' => 'Tên đăng nhập',
            'password' => 'Mật khẩu',
            'rememberMe' => 'Ghi nhớ',
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {

        if ($this->validate()) {
            return app('user')->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }

        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }

//    public $name;
//    public $password;
//    public $rememberMe = true;
//
//    private $_user = false;
//
//    /**
//     * @return array the validation rules.
//     */
//    public function rules()
//    {
//        return [
//            // username and password are both required
//            [['name', 'password'], 'required'],
//            // rememberMe must be a boolean value
//            ['rememberMe', 'boolean'],
//            // password is validated by validatePassword()
//            ['password', 'validatePassword'],
//        ];
//    }

//
//    /**
//     * Validates the password.
//     * This method serves as the inline validation for password.
//     *
//     * @param string $attribute the attribute currently being validated
//     * @param array  $params    the additional name-value pairs given in the rule
//     */
//    public function validatePassword($attribute, $params)
//    {
//        if (!$this->hasErrors()) {
//            $user = $this->getUser();
//
//            if (!$user || !$user->validatePassword($this->password)) {
//                $this->addError($attribute, 'Tên đăng nhập hoặc mật khẩu không đúng.');
//            }
//        }
//    }
//
//    /**
//     * Logs in a user using the provided username and password.
//     *
//     * @return bool whether the user is logged in successfully
//     */
//    public function login()
//    {
//        if ($this->validate()) {
////            $identity = \common\modules\auth\models\User::findOne(['id' => 1]);
//            $identity = \common\models\User::findOne(['id' => 1]);
//            return app('user')->login($identity);
////            return app('user')->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
//        }
//
//        return false;
//    }
//
//    /**
//     * Finds user by [[username]]
//     *
//     * @return User|null
//     */
//    public function getUser()
//    {
//        if ($this->_user === false) {
//            $this->_user = User::findByUsername($this->name);
//        }
//
//        return $this->_user;
//    }
}
