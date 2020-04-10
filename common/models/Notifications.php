<?php
namespace common\models;

use common\models\MyModel;
use Yii;

/**
 * This is the model class for table "notifications".
 *
 * @property string $id
 * @property string $type
 * @property int $notifiable_id
 * @property string $notifiable_type
 * @property int $user_id
 * @property string $data
 * @property string $message
 * @property string $read_at
 * @property string $created_at
 * @property string $updated_at
 */
class Notifications extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notifications';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['notifiable_id', 'user_id'], 'integer'],
            [['data', 'message'], 'string'],
            [['read_at', 'created_at', 'updated_at'], 'safe'],
            [['type', 'notifiable_type'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', 'Type'),
            'notifiable_id' => Yii::t('app', 'Notifiable ID'),
            'notifiable_type' => Yii::t('app', 'Notifiable Type'),
            'user_id' => Yii::t('app', 'User ID'),
            'data' => Yii::t('app', 'Data'),
            'message' => Yii::t('app', 'Message'),
            'read_at' => Yii::t('app', 'Read At'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
