<?php
namespace common\modules\notifications\models;
use Carbon\Carbon;
use common\models\MyModel;
use common\modules\notifications\behaviors\ReadableBehavior;
use common\modules\notifications\Notifier;
use yii\behaviors\AttributeTypecastBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * Database notification model
 * @property $level string
 * @property $subject string
 * @property $notifiable_type string
 * @property $notifiable_id int
 * @property $body string
 * @property $read_at string
 * @property $url string
 * @property $data json
 * @property $notifiable
 * @method  void markAsRead()
 * @method  void markAsUnread()
 * @method  bool read()
 * @method  bool unread()
 */
class Notification extends MyModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['level', 'notifiable_type', 'subject', 'body', 'url'], 'string'],
            ['notifiable_id', 'integer'],
            ['data', 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'value' => new Expression('NOW()'),
            ],
            ReadableBehavior::className(),
            [
                'class'              => BlameableBehavior::className(),
                'createdByAttribute' => 'user_id',
                'updatedByAttribute' => 'user_id',
            ]
        ];
    }

    public function getNotifiable()
    {
        return $this->hasOne($this->notifiable_type, ['id' => 'notifiable_id']);
    }

    public function getCreatedAtDiffText(){
        return $this->created_at ? Carbon::parse($this->created_at)->diffForHumans(): '';
    }

    public function getCreatedAtText(){
        if($this->created_at){
            $date = Carbon::parse($this->created_at);
            return $date->format('l d F').' lúc '.$date->format('h:i A');
        }

        return '';
    }

    public function getReadAtDiffText(){
        return $this->read_at ? Carbon::parse($this->read_at)->diffForHumans(): '';
    }

    public static function  send($recipients, $notifications){
        $notifier = new Notifier();
        $notifier->send($recipients, $notifications);
    }
}