<?php

namespace common\models;

use common\models\MyModel;
use Yii;

/**
 * This is the model class for table "dm_phuong".
 *
 * @property integer $gid
 * @property string $objectid
 * @property string $madvhc
 * @property string $caphc
 * @property string $maphuong
 * @property string $maquan
 * @property string $tenphuong
 * @property string $tenquan
 * @property string $soho
 * @property string $st_area_sh
 * @property string $st_length_
 * @property string $geom
 */
class DmPhuong extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dm_phuong';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['objectid', 'soho', 'st_area_sh', 'st_length_'], 'number'],
            [['geom'], 'string'],
            [['madvhc', 'maphuong', 'maquan', 'tenphuong'], 'string', 'max' => 50],
            [['caphc', 'tenquan'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'gid' => Yii::t('app', 'Gid'),
            'objectid' => Yii::t('app', 'Objectid'),
            'madvhc' => Yii::t('app', 'Madvhc'),
            'caphc' => Yii::t('app', 'Caphc'),
            'maphuong' => Yii::t('app', 'Maphuong'),
            'maquan' => Yii::t('app', 'Maquan'),
            'tenphuong' => Yii::t('app', 'Tenphuong'),
            'tenquan' => Yii::t('app', 'Tenquan'),
            'soho' => Yii::t('app', 'Soho'),
            'st_area_sh' => Yii::t('app', 'St Area Sh'),
            'st_length_' => Yii::t('app', 'St Length'),
            'geom' => Yii::t('app', 'Geom'),
        ];
    }

    public function getNonghos()
    {
        return $this->hasMany(Nongho::className(), ['maphuong' => 'maphuong']);
    }
}
