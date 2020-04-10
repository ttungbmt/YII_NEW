<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "dm_quan".
 *
 * @property integer $gid
 * @property string $objectid
 * @property string $ten_quan
 * @property string $mahc
 * @property string $caphc
 * @property string $soho
 * @property string $st_area_sh
 * @property string $st_length_
 * @property string $geom
 */
class DmQuan extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dm_quan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['objectid', 'soho', 'st_area_sh', 'st_length_'], 'number'],
            [['geom'], 'string'],
            [['ten_quan'], 'string', 'max' => 20],
            [['mahc', 'caphc'], 'string', 'max' => 50],
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
            'ten_quan' => Yii::t('app', 'Ten Quan'),
            'mahc' => Yii::t('app', 'Mahc'),
            'caphc' => Yii::t('app', 'Caphc'),
            'soho' => Yii::t('app', 'Soho'),
            'st_area_sh' => Yii::t('app', 'St Area Sh'),
            'st_length_' => Yii::t('app', 'St Length'),
            'geom' => Yii::t('app', 'Geom'),
        ];
    }

    public function getDmPhuongs()
    {
        return $this->hasMany(DmPhuong::className(), ['maquan' => 'mahc']);
    }
}
