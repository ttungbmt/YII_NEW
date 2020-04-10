<?php

namespace drought\models;

use Yii;

/**
 * This is the model class for table "hc_vung".
 *
 * @property int $id
 * @property string $tenvung
 * @property string $group
 * @property string $mavung
 */
class HcVung extends \drought\models\App
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hc_vung';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tenvung', 'group', 'mavung'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tenvung' => 'Tenvung',
            'group' => 'Group',
            'mavung' => 'Mavung',
        ];
    }

    public function getTinhs(){
        return $this->hasMany(HcTinh::class, ['matinh' => 'matinh_id'])->select('gid, matinh, tentinh')
            ->viaTable('hc_vung_tinh', ['mavung_id' => 'id']);
    }

}
