<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "activity_log".
 *
 * @property int $id
 * @property string|null $log_name
 * @property string $description
 * @property int|null $subject_id
 * @property string|null $subject_type
 * @property int|null $causer_id
 * @property string|null $causer_type
 * @property string|null $properties
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class ActivityLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'activity_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'required'],
            [['subject_id', 'causer_id'], 'default', 'value' => null],
            [['subject_id', 'causer_id'], 'integer'],
            [['properties'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['log_name', 'description', 'subject_type', 'causer_type'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'log_name' => 'Log Name',
            'description' => 'Description',
            'subject_id' => 'Subject ID',
            'subject_type' => 'Subject Type',
            'causer_id' => 'Causer ID',
            'causer_type' => 'Causer Type',
            'properties' => 'Properties',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
