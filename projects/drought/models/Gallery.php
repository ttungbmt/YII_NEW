<?php

namespace drought\models;

use Carbon\Carbon;
use drought\support\PgCommand;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use mohorev\file\UploadBehavior;
use ttungbmt\behaviors\UploadImageBehavior;
use ttungbmt\db\ActiveRecord;
use ttungbmt\db\Query;
use ttungbmt\gdal\Gdal;
use ttungbmt\support\facades\Http;
use Yii;
use yii\base\ModelEvent;
use yii\db\BaseActiveRecord;
use yii\db\Expression;
use yii\web\UploadedFile;
use function _\lowerCase;
use function _\upperCase;

/**
 * This is the model class for table "gallery".
 *
 * @property int $id
 * @property string $image
 * @property string $name
 * @property string $code
 * @property string $date
 * @property string $bands
 * @property string $folder
 * @property int $type
 */
class Gallery extends ActiveRecord
{
    const SCENARIO_UPLOAD = 'upload';
    const SCENARIO_CALC = 'calc';

    public $bands_str;
    public $dimension;


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

    public function init()
    {
        $this->on(UploadBehavior::EVENT_AFTER_UPLOAD, [$this, 'afterUpload']);

        parent::init(); // DON'T Forget to call the parent method.
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bands', 'date', 'dimension', 'folder', 'resample_id'], 'safe'],
            [['type'], 'integer'],
            [['name', 'code'], 'string', 'max' => 255],
            [['date'], 'date', 'format' => 'php:d/m/Y'],
            [['name', 'code'], 'string', 'max' => 255],
            ['code', 'match', 'pattern' => '/^[A-Za-z0-9_]\w*$/i'],
            ['image', 'file', 'extensions' => 'tif'],

            [['name', 'date'], 'required', 'on' => self::SCENARIO_CALC],
            [['expr', 'bands'], 'required', 'when' => function ($model) {
                return !UploadedFile::getInstance($this, 'image');
            }, 'on' => self::SCENARIO_CALC],
            ['type', 'default', 'value' => 2, 'on' => self::SCENARIO_CALC],

            [['code'], 'unique', 'on' => [self::SCENARIO_UPLOAD, self::SCENARIO_CALC]],
            [['image'], 'required', 'when' => function ($model) {
                return $model->isNewRecord;
            }, 'on' => self::SCENARIO_UPLOAD],
            ['type', 'default', 'value' => 1, 'on' => self::SCENARIO_UPLOAD],
            [['code', 'folder', 'name', 'date'], 'required', 'on' => self::SCENARIO_UPLOAD],
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
            'dimension' => 'Kích thước',
            'year' => 'Năm',
        ];
    }

    public function getViewTb()
    {
        return 'm_' . $this->code;
    }

    protected function importDB($source)
    {
        $gdal = new Gdal();
        $this->metadata = json_decode($gdal->gdalinfo($source, ['-json'])->run(), true);

        $table_name = $this->code;
        $m_view_name = $this->getViewTb();
        $db = Yii::$app->db;
        $db->createCommand("DROP TABLE IF EXISTS {$table_name} CASCADE")->execute();


        $gdal->rasterToPgSQL($source, $this->code)->run();

        $query = (new Query())->select('x, y, val, geom')->from(['vt' => (new Query())->select(new Expression('(ST_PixelAsPolygons(rast, 1)).*'))->from($table_name)])->andWhere(['<>', 'val', 128]);
        $viewSql = "CREATE MATERIALIZED  VIEW {$m_view_name} AS " . $query->createCommand()->getRawSql();

        $db->createCommand($viewSql)->execute();

        $style = 'grid_drought';
        if($this->folder == 'spi'){
            $style = 'grid_spi';
        } elseif ($this->folder == 'lst'){
            $style = 'grid_lst';
        } elseif ($this->folder == 'ndvi'){
            $style = 'grid_ndvi';
        }

        try {
            $client = function () {
                return Http::withBasicAuth('admin', 'geoserver')->withHeaders(['Content-Type' => 'application/xml']);
            };
            $url_feature = 'http://localhost:8080/geoserver/rest/workspaces/drought/datastores/drought/featuretypes';
            $url_style = 'http://localhost:8080/geoserver/rest/layers/drought:' . $m_view_name;

            $response = $client()->send('POST', $url_feature, ['body' => '<featureType><name>' . $m_view_name . '</name></featureType>']);
            $response = $client()->send('PUT', $url_style, ['body' => '<layer><defaultStyle><name>'.$style.'</name></defaultStyle></layer>']);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function saveRasterCalc()
    {
        if (!$this->validate()) return false;
        $this->folder = 'cdi';
        $this->year = Carbon::createFromFormat('d/m/Y', $this->date)->format('Y');
        $alphas = range('A', 'Z');
        $bands = collect(Gallery::find()->select('id, code, image')->andFilterWhere(['id' => $this->bands])->asArray()->all())
            ->map(function ($i, $k) use ($alphas) {
                return array_merge($i, ['alpha' => data_get($alphas, $k)]);
            })
            ->all();

        $upload = UploadedFile::getInstance($this, 'image');

        $this->type = 2;
        $this->code = (string)Str::of($this->name)->slug('_')->lower();
        $this->image = $this->generateNewName($this->code);


        if (!$upload) {
            $this->bands = implode(',', $this->bands);
            $expr = Str::of($this->expr)->replace('[', '{{')->replace(']', '}}');
            $messages = Arr::pluck($bands, 'alpha', 'code');
            $expr = trans($expr, $messages);

            $file = Yii::getAlias('@webroot/projects/drought/uploads/results/' . $this->image);
            $bands = collect($bands)->filter(function ($i, $k) use ($expr) {
                return Str::contains($expr, $i['alpha']);
            })
                ->map(function ($i, $k) use ($alphas) {
                    return ['-' . $i['alpha'] => Yii::getAlias('@webroot/projects/drought/uploads/' . $i['image'])];
                })
                ->collapse()
                ->all();


            $gdal = new Gdal();
            $gdal->o4w_env();
            $gdal->calc($bands, $file, $expr);
            if (file_exists($file)) unlink($file);

            $output = $gdal->run(null);
            $output = (string)Str::of($output)->match('/0 .. 10 .. 20 .. 30 .. 40 .. 50 .. 60 .. 70 .. 80 .. 90 .. 100 - Done/');

            if ($output) {
                $this->importDB($file);
            }
        } else {
            $upload->saveAs(Yii::getAlias('@webroot/projects/drought/uploads/results/' . $this->image));
            $file = Yii::getAlias('@webroot/projects/drought/uploads/results/' . $this->image);
            $this->importDB($file);
        }

        $this->symbology = $this->symbology ? $this->symbology : json_decode('[{"color":"#7f0000","start":"","end":"1","legend":"<=1 (Hạn khắc nghiệt)"},{"color":"#d7301f","start":"1","end":"2","legend":"1 - <=2 (Hạn nặng)"},{"color":"#fc8d59","start":"2","end":"3","legend":"2 - <=3 (Hạn trung bình)"},{"color":"#fdd49e","start":"3","end":"4","legend":"3 - <=4 (Hạn nhẹ)"},{"color":"#fff7ec","start":"4","end":"","legend":"> 4 (Không hạn)"}]', true);

        return $this->save();
    }

    public function getFileCalcUrl()
    {
        return Yii::getAlias('@web/projects/drought/uploads/results/' . $this->image);
    }

    public function getFileCalcPath()
    {
        return Yii::getAlias('@webroot/projects/drought/uploads/results/' . $this->image);
    }

    public function tiffExists()
    {
        return $this->image ? true : false;
    }

    public function getMVLayers(){

    }

    public function getFeatureMeta()
    {
        if (!$this->code) return optional([]);

        try {
            $response = Http::withBasicAuth('admin', 'geoserver')->get('http://localhost:8080/geoserver/rest/workspaces/drought/datastores/drought/featuretypes/m_' . $this->code . '.json');
            $json = (object)data_get($response->json(), 'featureType');
            return $json ? $json : optional([]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function deleteAllRelated()
    {
        $bool = $this->delete();
        $file = $this->getUploadPath();
        if (file_exists($file)) {
            unlink($file);
        }

        return $bool;
    }

    public function deleteAllRelatedCalc()
    {
        $bool = $this->delete();
        $file = $this->getFileCalcPath();

        if (file_exists($file)) unlink($file);

        $table_name = $this->code;
        $m_view_name = $this->getViewTb();
        $db = Yii::$app->db;

        try {
            $db->createCommand("DROP TABLE IF EXISTS {$table_name} CASCADE")->execute();

            $client = function () {
                return Http::withBasicAuth('admin', 'geoserver')->withHeaders(['Content-Type' => 'application/xml']);
            };
            $url_feature = 'http://localhost:8080/geoserver/rest/layers/drought:'.$this->getViewTb();
            $delete_feature = 'http://localhost:8080/geoserver/rest/workspaces/drought/styles/'.$this->getViewTb();
            
            $response1 = $client()->send('DELETE', $url_feature);
            $response2 = $client()->send('DELETE', $delete_feature);

            return true;
        } catch (\Exception $e) {
            dd($e);
            return false;
        }

        return false;
    }

    public function generateNewName($name, $ext = 'tif')
    {
        $unid = '_' . uniqid();
        $unid = '';
        return (string)Str::of($name)->slug('_')->lower()->append($unid . '.' . $ext);
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
                    $suffix = '_' . uniqid();
                    return (string)Str::of($this->code)->slug('_')->lower()->append($suffix . '.' . $file->extension);
                },
            ]
        ]);
    }

    public function saveImg(){

        if (!$this->validate()) return false;
        $this->year = Carbon::createFromFormat('d/m/Y', $this->date)->format('Y');
        switch ($this->folder){
            case 'spi':
                $this->symbology = $this->symbology ? $this->symbology : json_decode('[{"color":"#7f0000","start":"","end":"2","legend":"<=-2 (Hạn cực nặng)"},{"color":"#d7301f","start":"-2","end":"-1.5","legend":"-2 < SPI <= -1.5 (Hạn nặng)"},{"color":"#fc8d59","start":"-1.5","end":"-1","legend":"-1.5 < SPI <= -1 (Hạn vừa)"},{"color":"#fdd49e","start":"-1","end":"0","legend":"-1 < SPI <=0 (Hạn nhẹ)"},{"color":"#fff7ec","start":"0","end":"","legend":"SPI > 4 (Không hạn)"}]', true);
                break;
            case 'ndvi':
            case 'lst':
                $this->symbology = $this->symbology ? $this->symbology : json_decode('[{"color":"#7f0000","start":"","end":"-50","legend":"<=-50 (Hạn rất nặng)"},{"color":"#d7301f","start":"-50","end":"-25","legend":"-50 : <= -25 (Hạn nặng)"},{"color":"#fc8d59","start":"-25","end":"-10","legend":"-25 : <= -10 (Hạn vừa)"},{"color":"#fdd49e","start":"-10","end":"0","legend":"-10 : <= 0 (Hạn nhẹ)"},{"color":"#fff7ec","start":"0","end":"","legend":"> 0 (Không hạn)"}]', true);
                break;
        }

        $bool = $this->save();

        $file0 = $this->getUploadPath('image', true);
        if($this->resample_id){
            $img = Gallery::findOne($this->resample_id);
            $x_res = data_get($img, 'metadata.geoTransform.1');
            $y_res = data_get($img, 'metadata.geoTransform.5');
            $x_size = data_get($img, 'metadata.size.0');
            $y_size = data_get($img, 'metadata.size.1');
            if($x_res && $y_res){
                $gdal = new Gdal();
                $name = pathinfo($file0, PATHINFO_FILENAME);
                $file1 = (string)Str::of($file0)->replaceLast($name, $name . '_tmp1');
                $file2 = (string)Str::of($file0)->replaceLast($name, $name . '_tmp2');

                $gdal->translate($file0, $file1, [
                    '-tr' => "$x_res $y_res",
                    '-r' => 'bilinear',
                ])->run();

                $gdal->translate($file1, $file2, [
                    '-outsize' => "$x_size $y_size"
                ])->run();

                unlink($file0);
                unlink($file1);
                rename($file2, $file0);
            }
        }

        $this->importDB($file0);
        $this->removeAllAuxFiles();

        return $bool;
    }

    public function afterUpload($event)
    {
        $model = $event->sender;
        $file0 = $model->getUploadPath();
        $this->removeAllAuxFiles();
        $gdal = new Gdal();

         if (!$this->dimension) return null;

        $name = pathinfo($file0, PATHINFO_FILENAME);
        $file1 = (string)Str::of($file0)->replaceLast($name, $name . '_tmp');

        $gdal->translate($file0, $file1, [
            '-outsize' => $this->dimension
        ])->run();

        unlink($file0);
        rename($file1, $file0);

    }

    public function removeAllAuxFiles()
    {
        delete_all_files(Yii::getAlias('@webroot/projects/drought/uploads/*.jpg.aux.xml'));
    }

}
