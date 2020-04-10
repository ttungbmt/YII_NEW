<?php
namespace common\supports\data;

use kartik\export\ExportMenu;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class GridView extends \kartik\grid\GridView
{
    public $condensed = true;

//    public function init()
//    {
//        if (empty($this->pjaxSettings['options']['enablePushState'])) {
//            // $this->pjaxSettings['options']['enablePushState'] = true;
//        }
//
////        $this->export = [
////            'fontAwesome' => true,
////            'itemsAfter'  => [
////                '<li role="presentation" class="divider"></li>',
////                '<li class="dropdown-header">Xuất tất cả dữ liệu</li>',
////                ExportMenu::widget([
////                    'dataProvider'    => $this->dataProvider,
////                    'columns'         => $this->columns,
////                ])
////            ]
////        ];
//
//        parent::init();
//    }

    protected function initPanel()
    {
        parent::initPanel();

        if (!$this->bootstrap || !is_array($this->panel) || empty($this->panel)) {
            return;
        }
        $options = ArrayHelper::getValue($this->panel, 'options', []);
        $type = ArrayHelper::getValue($this->panel, 'type', 'default');
        $heading = ArrayHelper::getValue($this->panel, 'heading', '');
        $footer = ArrayHelper::getValue($this->panel, 'footer', '');
        $before = ArrayHelper::getValue($this->panel, 'before', '');
        $after = ArrayHelper::getValue($this->panel, 'after', '');
        $headingOptions = ArrayHelper::getValue($this->panel, 'headingOptions', ['class' => 'card-header bg-primary']);
        $titleOptions = ArrayHelper::getValue($this->panel, 'titleOptions', ['class' => 'card-title']);
        $footerOptions = ArrayHelper::getValue($this->panel, 'footerOptions', []);
        $beforeOptions = ArrayHelper::getValue($this->panel, 'beforeOptions', []);
        $afterOptions = ArrayHelper::getValue($this->panel, 'afterOptions', []);
        $summaryOptions = ArrayHelper::getValue($this->panel, 'summaryOptions', []);
        $panelHeading = '';
        $panelBefore = '';
        $panelAfter = '';
        $panelFooter = '';
        $isBs4 = $this->isBs4();



        if (isset($this->panelPrefix)) {
            static::initCss($options, $this->panelPrefix . $type);
        } else {
            $this->addCssClass($options, self::BS_PANEL);
            Html::addCssClass($options, $isBs4 ? "border-{$type}" : "panel-{$type}");
        }
        static::initCss($summaryOptions, $this->getCssClass(self::BS_PULL_RIGHT));
        $titleTag = ArrayHelper::remove($titleOptions, 'tag', ($isBs4 ? 'h5' : 'h3'));
        static::initCss($titleOptions, $isBs4 ? 'm-0' : $this->getCssClass(self::BS_PANEL_TITLE));

        if ($heading !== false) {
            $color = $isBs4 ? ($type === 'default' ? ' bg-light' : " text-white bg-{$type}") : '';
            static::initCss($headingOptions, $this->getCssClass(self::BS_PANEL_HEADING) . $color);
            $panelHeading = Html::tag('div', $this->panelHeadingTemplate, $headingOptions);
        }
        if ($footer !== false) {
            static::initCss($footerOptions, $this->getCssClass(self::BS_PANEL_FOOTER));
            $content = strtr($this->panelFooterTemplate, ['{footer}' => $footer]);
            $panelFooter = Html::tag('div', $content, $footerOptions);
        }
        if ($before !== false) {
            static::initCss($beforeOptions, 'kv-panel-before');
            $content = strtr($this->panelBeforeTemplate, ['{before}' => $before]);
            $panelBefore = Html::tag('div', $content, $beforeOptions);
        }
        if ($after !== false) {
            static::initCss($afterOptions, 'kv-panel-after');
            $content = strtr($this->panelAfterTemplate, ['{after}' => $after]);
            $panelAfter = Html::tag('div', $content, $afterOptions);
        }
        $out = strtr($this->panelTemplate, [
            '{panelHeading}' => $panelHeading,
            '{type}' => $type,
            '{panelFooter}' => $panelFooter,
            '{panelBefore}' => $panelBefore,
            '{panelAfter}' => $panelAfter,
        ]);


        $this->layout = Html::tag('div', strtr($out, [
            '{title}' => Html::tag($titleTag, $heading, $titleOptions),
            '{summary}' => Html::tag('div', '{summary}', $summaryOptions),
        ]), $options);
    }



//    protected function renderPanel()
//    {
//        parent::renderPanel();
//
//        if (!$this->bootstrap || !is_array($this->panel) || empty($this->panel)) {
//            return;
//        }
//
//        $type = ArrayHelper::getValue($this->panel, 'type', 'default');
//        $heading = ArrayHelper::getValue($this->panel, 'heading', '');
//        $footer = ArrayHelper::getValue($this->panel, 'footer', '');
//        $before = ArrayHelper::getValue($this->panel, 'before', '');
//        $after = ArrayHelper::getValue($this->panel, 'after', '');
//        $headingOptions = ArrayHelper::getValue($this->panel, 'headingOptions', []);
//        $footerOptions = ArrayHelper::getValue($this->panel, 'footerOptions', []);
//        $beforeOptions = ArrayHelper::getValue($this->panel, 'beforeOptions', []);
//        $afterOptions = ArrayHelper::getValue($this->panel, 'afterOptions', []);
//        $panelHeading = '';
//        $panelBefore = '';
//        $panelAfter = '';
//        $panelFooter = '';
//
//        if ($heading !== false) {
//            static::initCss($headingOptions, 'card-header bg-primary text-white header-elements-sm-inline pt-2 pb-2');
//            $content = strtr($this->panelHeadingTemplate, ['{heading}' => $heading]);
//            $panelHeading = Html::tag('div', $content, $headingOptions);
//        }
//        if ($footer !== false) {
//            static::initCss($footerOptions, 'card-footer');
////            $pageSize = [10 => '10 đối tượng', 20 => '20 đối tượng', 50 => '50 đối tượng', 100 => '100 đối tượng'];
////            if($this->dataProvider->pagination){
////                $footer = Html::tag('div', '<span class="mr-1">Hiển thị</span>  '.Html::dropDownList('pagination', $this->dataProvider->pagination->pageSize, $pageSize, ['class' => 'form-control']), ['class' => 'form-inline']).$footer;
////            } else {
////                $footer = '';
////            }
//            $content = strtr($this->panelFooterTemplate, ['{footer}' => $footer]);
//            $panelFooter = Html::tag('div', $content, $footerOptions);
//        }
//        if ($before !== false) {
//            static::initCss($beforeOptions, 'kv-panel-before');
//            $content = strtr($this->panelBeforeTemplate, ['{before}' => $before]);
//            $panelBefore = Html::tag('div', $content, $beforeOptions);
//        }
//        if ($after !== false) {
//            static::initCss($afterOptions, 'kv-panel-after');
//            $content = strtr($this->panelAfterTemplate, ['{after}' => $after]);
//            $panelAfter = Html::tag('div', $content, $afterOptions);
//        }
//
//        $dataProvider = $this->dataProvider;
//        $gridColumns = $this->columns;
//
//        if(!$this->filterSelector){
//            $this->filterSelector = "select[name='".$dataProvider->getPagination()->pageSizeParam."'],input[name='".$dataProvider->getPagination()->pageParam."']";
//        }
//
////        dd($this->export);
//        if(!$this->export){
////            $this->export = ExportMenu::widget(['dataProvider' => $dataProvider, 'columns' => $gridColumns]);
//        }
//
//        $this->layout = strtr(
//            $this->panelTemplate,
//            [
//                '{panelHeading}' => $panelHeading,
//                '{prefix}' => $this->panelPrefix,
//                '{type}' => $type,
//                '{panelFooter}' => $panelFooter,
//                '{panelBefore}' => $panelBefore,
//                '{panelAfter}' => $panelAfter,
//            ]
//        );
//    }
}