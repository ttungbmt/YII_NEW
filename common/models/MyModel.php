<?php

namespace common\models;

use Carbon\Carbon;
use common\supports\SyncRelationBehavior;
use nanson\postgis\behaviors\GeometryBehavior;
use paulzi\jsonBehavior\JsonBehavior;
use yii\base\Model;
use yii\behaviors\AttributesBehavior;
use yii\behaviors\AttributeTypecastBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\validators\DefaultValueValidator;
use yii\web\NotFoundHttpException;
use yii2tech\ar\position\PositionBehavior;

class MyModel extends ActiveRecord
{
    use HasRelationships;

    protected $timestamps = false;
    protected $blameables = false;
    protected $casts = [];
    protected $dates = [];
    protected $dateFormat = 'd/m/Y';
    protected $log = false;

    protected $geometryColumn = 'geom';

    protected $positionColumn;

    public $geometryType = GeometryBehavior::GEOMETRY_POINT;

    protected $relations = [];

    public function fillable($data)
    {
        return $this->load($data, '');
    }

    public function extraFields()
    {
        return ['geometry' => function ($model) {
            return $model->toGeometry();
        }];
    }

    public function getDraw($dataProvider) {
        $total = $dataProvider->getTotalCount();
        $models = $dataProvider->getModels();
        $columns = $this->getColumns();
        $fields = collect(ArrayHelper::index($columns, 'attribute'))->map(function ($v) {
            $data = opt($v);
            if ($data->value) return $data->value;
            return $data->attribute;
        })->all();


        $data = ArrayHelper::toArray($models, [
            self::className() => array_merge($fields, $this->extraAttrs())
        ]);

        return [
            'draw'            => (int)request('draw', 1),
            'recordsTotal'    => $total,
            'recordsFiltered' => $total,
            'data'            => $data,
        ];
    }

    public function loadAll($data){
        $c1 = $this->load($data);
        foreach ($this->getRelatedRecords() as $record){
            $c2 = Model::loadMultiple($record, $data);
            if(!$c2){ $c1 = false;}
        }
        return $c1;
    }

    public function validateAll(){
        $this->validate();
        foreach ($this->getRelatedRecords() as $record){
            Model::validateMultiple($record);
        }
    }

    public function toGeoJSON()
    {
        if (empty($this->geom)) return null;
        return ['type' => $this->geometryType, 'coordinates' => $this->geom];
    }

    public function toGeometry()
    {
        if (empty($this->geom)) return null;
        return ['type' => $this->geometryType, 'coordinates' => $this->geom];
    }

    public function toLatLng()
    {
        if (empty($this->geom)) return null;
        return ['lat' => $this->geom[1], 'lng' => $this->geom[0]];
    }

    public function toFeature()
    {
        if (empty($this->geom)) return null;
        return [
            'type'       => 'Feature',
            'properties' => $this->attributes,
            'geometry'   => $this->toGeoJSON()
        ];
    }

    public function titlePage($title)
    {
        return ($this->isNewRecord ? lang('Create') : lang('Update')) . ' ' . $title;
    }

    public function setDefaultValues()
    {
        $attributeNames = collect((array)$this->activeAttributes())->filter(function ($item) {
            $primaryKey = $this->primaryKey();
            return !in_array($item, $primaryKey);
        })->all();

        foreach ($this->getActiveValidators() as $validator) {
            if ($validator instanceof DefaultValueValidator) {
                $validator->validateAttributes($this, $attributeNames);
            }
        }
    }

    public static function findOrFail($condition)
    {
        if (($model = static::findOne($condition)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist');
        }
    }

    public function behaviors()
    {
        $tableSchema = static::getDb()->getSchema()->getTableSchema(static::tableName());
        $behaviors = parent::behaviors();

        if ($this->timestamps) {
            $behaviors['timestamp'] = [
                'class'      => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value'      => function ($event) use($tableSchema){
                    $type = data_get($tableSchema, 'columns.created_at.type');
                    if($type == 'integer'){
                        return time();
                    }

                    return Carbon::now();
                },
            ];
        }

        if ($this->blameables) {
            $behaviors['blameable'] = [
                'class'              => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ];
        }

        if ($tableSchema) {
            $geometryCol = $tableSchema->getColumn($this->geometryColumn);
            if ($geometryCol && $geometryCol->dbType = 'geometry') {
                $behaviors['geometry'] = [
                    'class'                => GeometryBehavior::className(),
                    'type'                 => $this->geometryType,
                    'attribute'            => $this->geometryColumn,
                    'skipAfterFindPostgis' => true,
                ];
            }

            $jsonCols = collect($tableSchema->columns)->filter(function ($item) {
                return $item->dbType == 'json';
            })->keys();
            if ($jsonCols->isNotEmpty()) {
                $behaviors['json'] = [
                    'class'      => JsonBehavior::className(),
                    'attributes' => $jsonCols->all(),
                ];
            }

            if ($this->positionColumn) {
                $behaviors['position'] = [
                    'class'             => PositionBehavior::className(),
                    'positionAttribute' => 'position',
                ];
            }
        }


        if (!empty($this->casts)) {
            $behaviors['typecast'] = [
                'class'                 => AttributeTypecastBehavior::className(),
                'attributeTypes' => [
                    'created_at'      => function ($value) {
                        return Carbon::parse($value);
                    },
                ],
                'typecastAfterValidate' => false,
                'typecastBeforeSave'    => false,
                'typecastAfterFind'     => true,
            ];
        }

        if (!empty($this->dates)) {
            $behaviors['datecast'] = [
                'class'      => AttributesBehavior::className(),
                'attributes' => collect($this->dates)->mapWithKeys(function ($item) {
                    return [$item => [
                        ActiveRecord::EVENT_AFTER_FIND    => $f1 = function ($event, $attribute) {
                            return app('formatter')->asDate($this->{$attribute});
                        },
                        ActiveRecord::EVENT_BEFORE_INSERT => $fn2 = function ($event, $attribute) {
                            $date = $this->{$attribute};
                            try {
                                return $date ? Carbon::createFromFormat('d/m/Y', $date) : null;
                            } catch (\Exception $e){
                                return $date;
                            }
                        },
                        ActiveRecord::EVENT_BEFORE_UPDATE => $fn2,
                        ActiveRecord::EVENT_AFTER_INSERT  => $f1,
                        ActiveRecord::EVENT_AFTER_UPDATE  => $f1,
                    ]];

                })->all(),
            ];
        }

//        $behaviors['linkall'] = [
//            'class'     => LinkAllBehavior::className(),
//        ];

        $behaviors['sync'] = [
            'class' => SyncRelationBehavior::className(),
        ];

//        if($this->relations) {
//            $behaviors['saveRelations'] = [
//                'class'     => SaveRelationsBehavior::className(),
//                'relations' => $this->relations
//            ];
//        }

        return $behaviors;
    }

    public static function find()
    {
        return new MyQuery(get_called_class());
    }

    protected function toAddress($sonha, $tenduong, $to_dp, $khupho, $tenphuong, $tenquan, $ten_tp = 'HCM')
    {

        $sonha = mb_ucwords(trim($sonha));
        $tenduong = mb_ucwords(trim($tenduong));
        $to_dp = mb_ucwords(trim($to_dp));
        $khupho = mb_ucwords(trim($khupho));
        $tenphuong = mb_ucwords(trim($tenphuong));
        $tenquan = mb_ucwords(trim($tenquan));
        $ten_tp = mb_ucwords(trim($ten_tp));

        if (empty($ten_tp)) {
            $ten_tp = 'HCM';
        }

        if ((!isset($sonha) || empty($sonha)) && (!isset($tenduong) || empty($tenduong)) && (!isset($to_dp) || empty($to_dp))
            && (!isset($khupho) || empty($khupho)) && (!isset($tenphuong) || empty($tenphuong)) && (!isset($tenquan) || empty($tenquan))) {
            return "Không rõ địa chỉ";
        }

        $diachi = "";

        if (isset($sonha) && !empty($sonha)) {
            if (isset($tenduong) && !empty($tenduong)) {
                $diachi .= $sonha . " ";
            } else {
                $diachi .= $sonha . ", ";
            }
        }

        if (isset($tenduong) && !empty($tenduong)) {
            $diachi .= $tenduong . ", ";
        }

        if (isset($to_dp) && !empty($to_dp)) {
            $diachi .= "Tổ " . $to_dp . ", ";
        }

        if (isset($khupho) && !empty($khupho)) {
            $diachi .= "Khu phố " . $khupho . ", ";
        }

        if (isset($tenphuong) && !empty($tenphuong)) {
            $diachi .= $tenphuong . ", ";
        }

        if (isset($tenquan) && !empty($tenquan)) {
            $diachi .= $tenquan . ", ";
        }

        $diachi .= $ten_tp;

        return $diachi;
    }

    public function saveMany($models)
    {
        foreach ($models as $key => $model) {
            $model->save();
        }

        return $models;
    }


    public static function pluck($value, $key = null)
    {
        return self::find()->asArray()->pluck($value, $key);
    }

    public function firstPrimaryKey()
    {
        return head($this->primaryKey());
    }

    public function getId()
    {
        return data_get($this, $this->firstPrimaryKey());
    }

    public function passes()
    {
        return $this->load(app('request')->post()) && $this->validate();
    }

    public function deleteRecursive($relations = [], $delete = true)
    {
        if ($relations == []) {
            $relations = $this->relations;
        }

        foreach ($relations as $relation) {
            if (is_array($this->$relation)) {
                foreach ($this->$relation as $relationItem) {
                    $relationItem->deleteRecursive();
                }
            } else {
                if (isset($this->$relation))
                    $this->$relation->deleteRecursive();
            }
        }

        if ($delete) {
            $this->delete();
        } else {
            $this->status = 0;
            $this->save();
        }

        return true;
    }

    public function getUser_created()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getUsername_created()
    {
        return $this->user_created ? $this->user_created->username : '- no user -';
    }

    public function getUser_updated()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    public function getUsername_updated()
    {
        if ($this->user_created && $this->user_updated) {
            return $this->user_updated->username;
        }
        return '- no user -';
    }

    public function inBound($attribute, $params)
    {
        $bool = true;
        $geoJSON = json_encode(['type' => 'Point', 'coordinates' => [$this->lng, $this->lat]]);
        $q = (new \yii\db\Query())->select(new Expression("ST_Intersects(ST_SetSRID(ST_GeomFromGeoJSON ('{$geoJSON}'), 4326), geom) bool"));

        if(role('quan')){
            $q->andWhere(['maquan' => userInfo()->ma_quan]);
            $q->from('hc_quan');
            $bool = head($q->one());
            if(!$bool){
                $this->addError('geom', 'Đối tượng không nằm trong ranh hành chính quận');
            }
            return $bool;
        } elseif (role('phuong')){
            $q->andWhere(['maphuong' => userInfo()->ma_phuong]);
            $q->from('hc_phuong');
            $bool = head($q->one());

            if(!$bool){
                $this->addError('geom', 'Đối tượng không nằm trong ranh hành chính phường');
            }
            return $bool;
        }

        return $bool;
    }

    public function fields()
    {
        $fields = parent::fields();
//        unset($fields['geom']);

        return array_merge($fields, [
            'geometry' => function ($model) {
                return $model->toGeometry();
            }
        ]);
    }
}