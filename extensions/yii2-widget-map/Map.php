<?php
namespace ttungbmt\map;

use kartik\helpers\Html;
use kartik\widgets\InputWidget;
use kartik\widgets\Widget;
use nanson\postgis\behaviors\GeometryBehavior;
use yii\base\InvalidConfigException;
use yii\base\Model;

/**
 * Usage example:
 *
 * ~~~
 * use kartik\date\DatePicker;
 * // usage without model
 * echo '<label>Check Issue Date</label>';
 * echo DatePicker::widget([
 *     'name' => 'check_issue_date',
 *     'value' => date('d-M-Y', strtotime('+2 days')),
 *     'options' => ['placeholder' => 'Select issue date ...'],
 *     'pluginOptions' => [
 *         'format' => 'dd-M-yyyy',
 *         'todayHighlight' => true
 *     ]
 * ]);
 * ~~~
 *
 * @author Truong Thanh Tung <ttungbmt@gmail.com>
 * @since  1.0
 */
class Map extends Widget
{
    public static $autoIdPrefix = 'reactMap-';

    public $model;

    public $center;

    public $zoom;

    public $disabled = false;

    public $options = [];

    public $defaultOptions = [];

    public $defaultPluginOptions = [
        'mapOptions' => [
            'style'       => [
                'height' => 400,
                'width'  => '100%',
                'margin' => '0 auto',
            ],
            'zoomControl' => false,
            'zoom'        => 14,
            'center'      => [10.804476, 106.639384],
        ]
    ];

    public $pluginOptions = [

    ];


    public $pluginName = 'reactMap';

    public function init()
    {
        if (!$this->hasModel()) {
            throw new InvalidConfigException("'model' properties must be specified.");
        }

        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->hasModel() ? Html::getInputId($this->model, 'map') : $this->getId();
        }

        parent::init();
        $this->initOptions();
        $this->registerAssets();
    }

    protected function hasModel()
    {
        return $this->model instanceof Model;
    }

    public function run()
    {
        parent::run();
        $this->renderWidget();
    }

    public function initOptions()
    {
        $model = $this->model;
        $this->pluginOptions['layers'] = require(__DIR__.'./_layers.php') ;

        if(!data_get($this, 'pluginOptions.drawOptions')){
            if($model->geometryType === GeometryBehavior::GEOMETRY_POINT){
                $this->pluginOptions['drawOptions'] = [
                    'draw' => [
                        'marker' => true,
                        'circle' => true,
                    ]
                ];
            }
            elseif (in_array($model->geometryType, [GeometryBehavior::GEOMETRY_MULTIPOLYGON, GeometryBehavior::GEOMETRY_POLYGON])){
                $this->pluginOptions['drawOptions'] = [
                    'draw' => [
                        'rectangle' => true,
                        'polygon' => true,
                    ]
                ];
            }
            elseif (in_array($model->geometryType, [GeometryBehavior::GEOMETRY_LINESTRING])){
                $this->pluginOptions['drawOptions'] = [
                    'draw' => [
                        'polyline' => true,
                    ]
                ];
            }
        }

        if($this->center){
            $this->pluginOptions['mapOptions']['center'] = $this->center;
        }

        if($model->toArray() && $model->geom){
            $feature = array_merge($model->toArray(), ['geometry' => $model->toGeometry()]);
            unset($feature['geom']);
            $this->pluginOptions['features'] = [$feature];
        }
    }

    protected function registerAssets()
    {
        $view = $this->getView();
        MapAsset::register($view);
        $this->registerPlugin($this->pluginName);
    }

    protected function renderWidget()
    {
        echo $this->render('./index', ['options' => $this->options]);
    }
}
