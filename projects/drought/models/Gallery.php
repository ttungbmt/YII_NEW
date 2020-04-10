<?php

namespace drought\models;

use drought\support\PgCommand;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use ttungbmt\behaviors\UploadImageBehavior;
use ttungbmt\db\ActiveRecord;
use ttungbmt\db\Query;
use ttungbmt\gdal\Gdal;
use Yii;
use yii\db\Expression;
use function Clue\StreamFilter\fun;

/**
 * This is the model class for table "gallery".
 *
 * @property int $id
 * @property string $image
 * @property string $name
 * @property string $code
 * @property string $date
 * @property string $bands
 * @property int $type
 */
class Gallery extends ActiveRecord
{
    const SCENARIO_UPLOAD = 'upload';
    const SCENARIO_CALC = 'calc';

    public $bands_str;

    protected $timestamps = true;

    public $dates = ['date'];

    public $casts = [
        'date' => 'datestr'
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gallery';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bands', 'date'], 'safe'],
            [['type'], 'integer'],
            [['name', 'code'], 'string', 'max' => 255],
            [['date'], 'date', 'format' => 'php:d/m/Y'],
            [['name', 'code'], 'string', 'max' => 255],
            ['code', 'match', 'pattern' => '/^[A-Za-z0-9_]\w*$/i'],
            ['image', 'file', 'extensions' => 'tif'],

            [['name', 'expr', 'bands'], 'required', 'on' => self::SCENARIO_CALC],
            ['type', 'default', 'value' => 2, 'on' => self::SCENARIO_CALC],
            [['code'], 'required', 'on' => self::SCENARIO_UPLOAD],
            [['image'], 'required', 'when' => function ($model) {
                return $model->isNewRecord;
            }, 'on' => self::SCENARIO_UPLOAD],
            ['type', 'default', 'value' => 1, 'on' => self::SCENARIO_UPLOAD],


        ];
    }

    public function beforeSave($insert)
    {
        $this->code = Str::lower($this->code);

        return parent::beforeSave($insert);
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'image' => 'Tệp tin',
            'name' => 'Tên ảnh',
            'code' => 'Mã ảnh',
            'date' => 'Ngày file ảnh',
        ];
    }

    public function saveRasterCalc()
    {
        if (!$this->validate()) return false;
        $alphas = range('A', 'Z');
        $bands = collect(Gallery::find()->select('id, code, image')->andFilterWhere(['id' => $this->bands])->asArray()->all())
            ->map(function ($i, $k) use ($alphas) {
                return array_merge($i, ['alpha' => data_get($alphas, $k)]);
            })
            ->all();


        $this->type = 2;
        $this->bands = implode($this->bands, ',');
        $this->code = $this->name;
        $this->image = $this->generateNewName($this->code);

        $expr = Str::of($this->expr)->replace('[', '{{')->replace(']', '}}');
        $messages = Arr::pluck($bands, 'alpha', 'code');
        $expr = trans($expr, $messages);

        $file = Yii::getAlias('@webroot/projects/drought/uploads/results/' . $this->image);
        $bands = collect($bands)->filter(function ($i, $k) use ($expr) {
            return Str::contains($expr, $i['alpha']);
        })
            ->map(function ($i, $k) use ($alphas) {
                return ['-'.$i['alpha'] => Yii::getAlias('@webroot/projects/drought/uploads/' . $i['image'])];
            })
            ->collapse()
            ->all();

        $gdal = new Gdal();
        $gdal->o4w_env();
        $gdal->calc($bands, $file, $expr);
        $output = $gdal->run(null);
        $output = (string)Str::of($output)->match('/0 .. 10 .. 20 .. 30 .. 40 .. 50 .. 60 .. 70 .. 80 .. 90 .. 100 - Done/');

        if($output){
            $table_name = $this->code;
            $db = Yii::$app->db;
            $db->createCommand("DROP TABLE IF EXISTS {$table_name} CASCADE")->execute();

            $source =  Yii::getAlias('@webroot/projects/drought/uploads/results/' . $this->image);
            $gdal->rasterToPgSQL($source, $this->code)->run();

            $query = (new Query())->select('x, y, val, geom')->from(['vt' => (new Query())->select(new Expression('(ST_PixelAsPolygons(rast, 1)).*'))->from($table_name)])->andWhere(['<>', 'val', 128]);
            $viewSql = "CREATE MATERIALIZED  VIEW m_{$table_name} AS ".$query->createCommand()->getRawSql();
            $db->createCommand($viewSql)->execute();
        }

        return $this->save();
    }



    protected function generateTiffCalc(){

    }

    public function deleteImage()
    {
        $file = $this->getUploadFile();
        if (file_exists($file)) {
            unlink($file);
        }
    }

    public function deleteAllRelated()
    {
        $this->delete();
        $this->deleteImage();
    }

    public function generateNewName($name, $ext = 'tif')
    {
//        $unid = '_' . uniqid();
        $unid = '';
        return (string)Str::of($name)->slug('_')->lower()->append($unid. '.'.$ext);
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'upload' => [
                'class' => UploadImageBehavior::class,
                'attribute' => 'image',
                'scenarios' => [self::SCENARIO_UPLOAD],
                'path' => '@webroot/projects/drought/uploads',
                'url' => '@web/projects/drought/uploads',
                'generateNewName' => function ($file) {
                    return (string)Str::of($this->code)->slug('_')->lower()->append('_' . uniqid() . '.' . $file->extension);
                }
            ]
        ]);
    }

}
