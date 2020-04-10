<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$class = $generator->modelClass;
$searchModelClass = StringHelper::basename($generator->searchModelClass);
$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();
$enableAjax = $generator->enableAjax;
$enableHc = $generator->enableHc;
$displayHc = false;
if($enableHc) {
    $class = $generator->modelClass;
    $columns = $class::getTableSchema()->getColumnNames();
    $quan = $generator->quan;
    $phuong = $generator->phuong;
    if(in_array($quan, $columns) && in_array($phuong, $columns)) {
        $displayHc = true;
    } else {
        $displayHc = false;
    }
}


echo "<?php\n";
?>
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */
CrudAsset::register($this);

$this->title = "<?= $generator->title ?>";
<?php if($displayHc) :
    $devine_class = explode("\\", $class)?>
    $partial = app('request')->get('partial') || app('request')->isAjax || (isset($partial) ? $partial : false);
    $suffix = '-<?= $devine_class[0]?>';
    $tableId = 'crud-datatable' . $suffix;
<?php endif; ?>
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index">
    <div id="ajaxCrudDatatable">
        <?="<?="?>GridView::widget([
        <?php if($displayHc): ?>
            'id'=>$tableId,
            'pjaxSettings' => [
            'options' => [
                'id' => $pjaxContainer = ('kv-pjax-container' . $suffix),
                'enablePushState' => $partial ? false : true,
                ],
            ],
        <?php else : ?>
            'id'=> 'crud-datatable',
        <?php endif; ?>
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'filterSelector' => 'select[name="pagination"]',
            'pjax'=>true,
            'columns' => require(__DIR__.'/_columns.php'),
            'toolbar'=> [
                ['content'=>
                    Html::a('Thêm mới', ['create'],
                    [<?= $enableAjax ? "'role'=>'modal-remote'" : "'target'=>'_blank'";?>,'title'=> 'Thêm mới <?=$generator->title?>','class'=>'btn btn-default', <?= $enableAjax ? "" : "'data-pjax'=> '0'";?>]).
                    Html::a('<i class="icon-reload-alt"></i>', [''],
                    ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=> lang('Reset Grid')]).
                    '{toggleData}'.
                    '{export}'
                ],
            ],          
            'striped' => true,
            'condensed' => true,
            'responsive' => true,          
            'panel' => [
                'type' => 'primary', 
                'heading' => 'Danh sách <?=$generator->title?>',
            ]
        ])<?="?>\n"?>
    </div>
</div>
<?='<?php Modal::begin([
    "id"=> "ajaxCrudModal",
    "footer"=> "",// always need it for jquery plugin
])?>'."\n"?>
<?='<?php Modal::end(); ?>'."\n"?>

<?php if($displayHc):?>
    <?='<?= $this->render("../depdrop_phuong/depdrop_phuong", [
    "selector"=>"'?><?=$searchModelClass.'['.$phuong.']", "pjaxContainer" => $pjaxContainer
]) ?>'?>
<?php endif; ?>




