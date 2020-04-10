<?php

namespace common\modules\auth\forms;

use common\models\User;
use common\modules\auth\models\UserInfo;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

class UserForm extends User
{
    public $fullname;
    public $chucdanh;
    public $phone;
    public $address;
    public $gender;

    public $new_password;
    public $new_password_repeat;

    public $roles;

    protected $fillable = ['fullname', 'chucdanh', 'phone', 'address', 'gender'];

    const SCENARIO_RESET_PASS = 'resetPassword';
    const SCENARIO_REGISTER = 'register';

    public $repeat_password_hash;

    public function init()
    {
        parent::init();
        $this->loadDefaultValues();
    }


    public function afterFind()
    {
        parent::afterFind();
        foreach ($this->fillable as $attr) {
            $this->{$attr} = data_get($this->info, $attr);
        }
        $this->roles = array_keys(auth()->getRolesByUser($this->id));
    }

    public function attributeLabels()
    {
        return array_merge((new UserInfo)->attributeLabels(), parent::attributeLabels(), [
            'repeat_password_hash' => 'Nhập lại mật khẩu',
            'new_password' => 'Mật khẩu mới',
            'new_password_repeat' => 'Nhập lại mật khẩu mới',
            'roles' => 'Phân quyền không được để trống'
        ]);
    }


    public function rules()
    {
        return [
            ['username', 'unique'],
            [['username', 'email', 'fullname', 'roles'], 'required'],
            ['roles', 'each', 'rule' => ['required']],
            [['email'], 'email'],
            [['password_hash', 'repeat_password_hash'], 'required', 'on' => [self::SCENARIO_REGISTER]],
            [['new_password', 'new_password_repeat'], 'required', 'on' => [self::SCENARIO_RESET_PASS]],
            ['repeat_password_hash', 'compare', 'compareAttribute' => 'password_hash', 'skipOnEmpty' => false, 'message' => 'Nhập mật khẩu không khớp', 'on' => [self::SCENARIO_REGISTER]],
            ['new_password_repeat', 'compare', 'compareAttribute' => 'new_password', 'skipOnEmpty' => false, 'message' => 'Nhập mật khẩu không khớp', 'on' => [self::SCENARIO_RESET_PASS]],
            [['fullname', 'chucdanh', 'address', 'phone'], 'string'],
            [['gender', 'status'], 'integer'],
        ];
    }


    public function saveUser()
    {
        $data = request()->post();
        if ($this->isNewRecord) {
            $this->scenario = self::SCENARIO_REGISTER;
        }

        $info = $this->info ? $this->info : new UserInfo();

        $isPost = $_POST && $this->load($data) && $info->load($data, $this->formName());
        if ($isPost && $this->validate()) {
            $this->save();
            $this->save();
            $info->save();
            $this->link('info', $info);

            auth()->updateRolesUser($this->id, data_get($data, $this->formName(). '.roles'));

            return true;
        }
        return false;
    }

    public function saveNewPass()
    {
        $this->scenario = self::SCENARIO_RESET_PASS;
        $this->load(request()->post());
        $this->validate();


        if (!$this->validate()) return false;
        $this->password_hash = $this->new_password;
        $this->save();
        return true;
    }

}