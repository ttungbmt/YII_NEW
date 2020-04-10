<?php
namespace drought\models;
use drought\support\PgCommand;
use Illuminate\Support\Str;
use ttungbmt\behaviors\UploadImageBehavior;
use ttungbmt\db\ActiveRecord;
use ttungbmt\gdal\Gdal;
use Yii;
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
            [['image'], 'required', 'when' => function($model){
                return $model->isNewRecord;
            },'on' => self::SCENARIO_UPLOAD],
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

    public function saveRasterCalc(){
        if(!$this->validate()) return false;

        $this->type = 2;
        $this->bands = implode($this->bands, ',');
        $this->code = $this->name;

        $file = Yii::getAlias('@webroot/projects/drought/uploads/results/'.$this->image);
        $cmd = new PgCommand();
        $cmd->rasterToDb($file, strtolower($this->code));

        return $this->save();
    }

    public function deleteImage(){
        $file = $this->getUploadFile();
        if(file_exists($file)){
            unlink($file);
        }
    }

    public function deleteAllRelated()
    {
        $this->delete();
        $this->deleteImage();
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
                'generateNewName' => function($file){
                    return (string)Str::of($this->code)->slug('_')->lower()->append(uniqid().'.'.$file->extension);
                }
            ]
        ]);
    }

}
