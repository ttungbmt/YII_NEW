<?php

namespace common\modules\auth\models;

use common\models\MyModel;
use Yii;
use yii\rbac\Item;

/**
 * This is the model class for table "auth_item".
 *
 * @property string $name
 * @property integer $type
 * @property string $description
 * @property string $rule_name
 * @property string $data
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property AuthAssignment[] $authAssignments
 * @property AuthRule $ruleName
 * @property AuthItemChild[] $authItemChildren
 * @property AuthItemChild[] $authItemChildren0
 * @property AuthItem[] $children
 * @property AuthItem[] $parents
 */
class AuthItem extends MyModel
{
    const PERMISSIONS = 'permissions';
    const ROLES = 'roles';

    public $roles;
    protected $permissions;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type', 'description'], 'required'],
            [['type', 'created_at', 'updated_at'], 'integer'],
            [['description', 'data'], 'string'],
            [['name', 'rule_name'], 'string', 'max' => 64],
            [['rule_name'], 'exist', 'skipOnError' => true, 'targetClass' => AuthRule::className(), 'targetAttribute' => ['rule_name' => 'name']],
            ['permissions', 'required', 'on' => self::ROLES],
            [['permissions', 'roles'], 'safe'],
            [['name'], 'match', 'pattern' => '/^[.a-z_-]+$/u', 'message' => '{attribute} chỉ gồm các ký tự chữ thường a-z và ký tự "_"']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Tên phân quyền'),
            'type' => Yii::t('app', 'Loại'),
            'description' => Yii::t('app', 'Mô tả'),
            'rule_name' => Yii::t('app', 'Rule Name'),
            'data' => Yii::t('app', 'Dữ liệu'),
            'created_at' => Yii::t('app', 'Ngày tạo'),
            'updated_at' => Yii::t('app', 'Ngày cập nhật'),

            'roles' => Yii::t('app', 'Phân quyền người dùng'),
            'permissions' => Yii::t('app', 'Phân quyền truy cập'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignment::className(), ['item_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRuleName()
    {
        return $this->hasOne(AuthRule::className(), ['name' => 'rule_name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChildren()
    {
        return $this->hasMany(AuthItemChild::className(), ['parent' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChildren0()
    {
        return $this->hasMany(AuthItemChild::className(), ['child' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(AuthItem::className(), ['name' => 'child'])->viaTable('auth_item_child', ['parent' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParents()
    {
        return $this->hasMany(AuthItem::className(), ['name' => 'parent'])->viaTable('auth_item_child', ['child' => 'name']);
    }

    public function saveRoles(){
        $data = request()->post();

        $this->scenario = self::ROLES;

        if($this->load($data) && $this->validate()){
            $perms = array_keys(auth()->getPermissionsByRole($this->name));
            $this->removeAllPerms($perms);
            foreach ($this->permissions as $item){
                $perm = auth()->getPermission($item);
                $role = auth()->getRole($this->name);
                auth()->addChild($role, $perm);
            }

            $this->save();
            return true;
        }

        return false;
    }

    protected function removeAllPerms($perms){
        foreach ($perms as $item){
            $perm = auth()->getPermission($item);
            $role = auth()->getRole($this->name);
            auth()->removeChild($role, $perm);
        }
    }

    public function savePerm(){
        $this->type = Item::TYPE_PERMISSION;

        if (!$this->validate()) {
            return false;
        }
        $this->save();
        return true;
    }

    public function saveRole(){
        $this->type = Item::TYPE_ROLE;

        if (!$this->validate()) {
            return false;
        }

        $this->save();
        return true;
    }

    public function getPermissions(){
        $keys = array_keys(auth()->getPermissionsByRole($this->name));
        return array_combine($keys, $keys);
    }

//    public function behaviors()
//    {
//        return array_merge(parent::behaviors(), [
//            'log' => [
//                'class' => AuthItemBehavior::className(),
//            ]
//        ]);
//    }

}
