<?php
namespace common\models;

/**
 * This is the model class for table "bookmark".
 *
 * @property int $gid
 * @property string $name
 * @property string $description
 * @property string $geom
 * @property string $ip
 * @property int $created_by
 * @property int $updated_by
 */
class Bookmark extends MyModel
{
    protected $timestamps = true;
    protected $blameables = true;

    public $geometry;
    public $distance;

    public function fields()
    {
        $parent = parent::fields();
        unset($parent['geom']);
        return $parent;
    }

    public function extraFields()
    {
        return [
            'geometry' => function ($item) {
                if($item->geom) return [
                    'type'        => $item->geometryType,
                    'coordinates' => $item->geom,
                ];

                return null;
            },
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bookmark';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['geom'], 'safe'],
            [['description'], 'string'],
            [['created_by', 'updated_by'], 'default', 'value' => null],
            [['created_by', 'updated_by'], 'integer'],
            [['name', 'ip'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'gid' => 'Gid',
            'name' => 'Name',
            'description' => 'Description',
            'geom' => 'Geom',
            'ip' => 'Ip',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }
}
