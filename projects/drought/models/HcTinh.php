<?php

namespace drought\models;

use Yii;

/**
 * This is the model class for table "hc_tinh".
 *
 * @property int $gid
 * @property string $matinh
 * @property string $tentinh
 * @property string $ten_en
 * @property string $cap
 * @property string $geom
 * @property string $tinh_en
 * @property int $order
 */
class HcTinh extends App
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hc_tinh';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['geom'], 'string'],
            [['order'], 'default', 'value' => null],
            [['order'], 'integer'],
            [['matinh', 'tentinh', 'ten_en', 'cap'], 'string', 'max' => 254],
            [['tinh_en'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'gid' => 'Gid',
            'matinh' => 'Matinh',
            'tentinh' => 'Tentinh',
            'ten_en' => 'Ten En',
            'cap' => 'Cap',
            'geom' => 'Geom',
            'tinh_en' => 'Tinh En',
            'order' => 'Order',
        ];
    }

}
