<?php
namespace ttungbmt\amcharts;

use kartik\widgets\Widget;
use yii\bootstrap\Html;

class AmCharts extends Widget
{
    public static $autoIdPrefix = 'amcharts-';

    const TYPE_BAR = 'serial';
    const TYPE_LINE = 'serial';
    const TYPE_PIE = 'pie';

    public $width = '100%';

    public $height = '400px';

    public $title;

    public $type = self::TYPE_BAR;

    public $valueAxes;

    public $dataProvider;

    public $pluginOptions = [];

    public $pluginName = 'amcharts';

    public $defaultPluginOptions = [
        'language' => 'vi',
        'fontFamily' => 'Arial',
        'fontSize' => 12,
        'theme' => 'light',

        'valueAxes' => [
            ['title' => 'Axes title']
        ],

        'startDuration' => 1,
        'categoryAxis' => ['gridPosition' => 'start'],
        'export' => ['enabled' => true, 'libs' => ['autoLoad' => false]],
//        'mouseWheelZoomEnabled' => true,
//        'chartCursor' => [
//            'enabled' => false,
//            'categoryBalloonEnabled' => false,
//            'categoryBalloonDateFormat' => 'DD MMMM, YYYY',
//            'cursorPosition' => 'mouse',
//            'oneBalloonOnly' => true,
//            'cursorColor' => '', #03A9F4',
//
//            'valueLineBalloonEnabled' => true,
//            'valueLineAlpha' => 0.2,
//
//            'showNextAvailable' => true,
//            'pan' => true,
//        ],
//        'chartScrollbar' => [
//            'enabled' => false,
//            'scrollbarHeight' => 2,
//            'offset' => -1,
//            'backgroundAlpha' => 0.1,
//            'backgroundColor' => "#888888",
//            'selectedBackgroundColor' => "#67b7dc",
//            'selectedBackgroundAlpha' => 1,
//        ],
//        "valueScrollbar" => [
//            "offset" => 50,
//            "scrollbarHeight" => 10,
//        ],


        'titles' => [['text' => 'Biểu đồ 1', 'size' => 16, 'color' => '#FF8000']],
        'categoryField' => 'category',
        'graphs' => [
            [
                'type' => 'column', 'valueField' => 'column', 'fillAlphas' => 1, 'lineColor' => '#04cef2', 'balloonText' => '[[value]]',
                'columnWidth' => 0.14
            ]
        ],
        'dataProvider' => [
            ['category' => 'category 1', 'column' => 100],
            ['category' => 'category 2', 'column' => 200],
            ['category' => 'category 3', 'column' => 2],
        ],
    ];

    public function init()
    {
        parent::init();
        $this->initOptions();
    }

    protected function initOptions()
    {
        Html::addCssClass($this->options, 'amcharts ' . $this->type);
        $this->options = array_merge([
            'style' => "width: {$this->width}; height: {$this->height};"
        ],  $this->options);

        $this->pluginOptions = array_merge([
            'type' => $this->type,
            'dataProvider' => $this->dataProvider,
            'valueAxes' => $this->valueAxes,
        ], $this->pluginOptions);

        $this->registerAssets();
    }

    public function run()
    {
        parent::run();
        $this->renderAmCharts();
    }

    protected function renderAmCharts()
    {
        echo Html::tag('div', '', $this->options);
    }

    public function registerAssets()
    {
        $view = $this->getView();
        $id = $this->options['id'];
        AmChartsAsset::register($view);

        $this->registerPluginOptions($this->pluginName);
        $js = "AmCharts.makeChart('{$id}', {$this->_hashVar});";
        $view->registerJs($js);
    }
}