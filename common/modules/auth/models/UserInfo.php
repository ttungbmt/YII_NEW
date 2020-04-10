<?php

namespace common\modules\auth\models;
use common\models\MyModel;
use pcd\models\HcPhuong;
use pcd\models\HcQuan;
use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $status
 * @property string  $fullname
 * @property string  $phone
 * @property string  $email
 * @property integer $ma_phuong
 * @property integer $ma_quan
 */
class UserInfo extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_info';
    }

    public function getPhuong()
    {
        return $this->hasOne(DmPhuong::className(), ['ma_phuong' => 'ma_phuong']);
    }

    public function getQuan()
    {
        return $this->hasOne(DmQuan::className(), ['ma_quan' => 'ma_quan']);
    }

    public function getHcPhuong()
    {
        return $this->hasOne(HcPhuong::className(), ['maphuong' => 'maphuong']);
    }

    public function getHcQuan()
    {
        return $this->hasOne(HcQuan::className(), ['maquan' => 'maquan']);
    }
//
//    public function getUser()
//    {
//        return $this->hasOne(UserInfo::className(), ['id' => 'user_id']);
//    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'ma_phuong', 'ma_quan', 'gender'], 'integer'],
            [['fullname', 'email', 'address', 'phone'], 'string', 'max' => 255],
            [['fullname', 'chucdanh',
//                'phone'
            ], 'required'],
            [['ma_phuong', 'ma_quan'], 'default', 'value' => null], // Validate yii2 k bắt '' vs integer
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'        => Yii::t('app', 'ID'),
            'user_id'   => Yii::t('app', 'User ID'),
            'status'    => Yii::t('app', 'Tình trạng'),
            'fullname'  => Yii::t('app', 'Họ tên'),
            'phone'     => Yii::t('app', 'Điện thoại'),
            'email'     => Yii::t('app', 'Email'),
            'ma_phuong' => Yii::t('app', 'Mã phường'),
            'ma_quan'   => Yii::t('app', 'Mã quận'),
            'chucdanh'  => Yii::t('app', 'Chức danh'),
            'gender'  => Yii::t('app', 'Giới tính'),
            'address'  => Yii::t('app', 'Địa chỉ'),
        ];
    }
}
