<?php
namespace common\libs\activitylog\models;

use Carbon\Carbon;
use common\models\MyModel;
use common\models\User;
use common\modules\auth\models\UserInfo;
use Yii;

/**
 * This is the model class for table "activity_log".
 *
 * @property integer $id
 * @property string $log_name
 * @property string $description
 * @property integer $subject_id
 * @property string $subject_type
 * @property integer $causer_id
 * @property string $causer_type
 * @property string $properties
 * @property string $created_at
 * @property string $updated_at
 */
class Activity extends MyModel
{
    protected $timestamps = true;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activity_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'required'],
            [['subject_id', 'causer_id'], 'integer'],
//            [['properties'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['log_name', 'description', 'subject_type', 'causer_type'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'log_name' => Yii::t('app', 'Log Name'),
            'description' => Yii::t('app', 'Hành động'),
            'subject_id' => Yii::t('app', 'Subject ID'),
            'subject_type' => Yii::t('app', 'Subject Type'),
            'causer_id' => Yii::t('app', 'Causer ID'),
            'causer_type' => Yii::t('app', 'Causer Type'),
            'properties' => Yii::t('app', 'Properties'),
            'created_at' => Yii::t('app', 'Ngày tạo'),
            'updated_at' => Yii::t('app', 'Ngày cập nhật'),
        ];
    }

    public function getCreatedAt()
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }

    public function getCauser()
    {
//        return $this->hasOne($this->causer_type, ['id' => 'causer_id']);
        return $this->hasOne(User::class, ['id' => 'causer_id']);
    }

    public function getUserInfo()
    {
        return $this->hasOne(UserInfo::class, ['user_id' => 'causer_id']);
    }

    public function getSubject()
    {
        return $this->hasOne($this->subject_type, ['id' => 'subject_id']);
    }
}
