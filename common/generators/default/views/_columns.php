<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;


/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$modelClass = StringHelper::basename($generator->modelClass);
$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();
$actionParams = $generator->generateActionParams();
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
use yii\helpers\Url;
<?php if ($displayHc) : ?>
use kartik\widgets\DepDrop;
use yii\bootstrap\Html;

$dmQuan = app('api')->get('dm_quan');
$selectOptions = ['prompt' => 'Tất cả', 'class' => 'form-control'];

$depdrop = DepDrop::widget([
'attribute' => 'maphuong',
'model' => $searchModel,
'options' => ['prompt' => 'Chọn phường...'],
'pluginOptions' => [
    'depends' => ['maquan'],
    'url' => url(['/api/dm/phuong']),]
]);
<?php endif; ?>
return [
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
    <?php
    $count = 0;
    foreach ($generator->getColumnNames() as $name) {   
        if ($name=='id'||$name=='created_at'||$name=='updated_at'){
            echo "    // [\n";
            echo "        // 'class'=>'\kartik\grid\DataColumn',\n";
            echo "        // 'attribute'=>'" . $name . "',\n";
            echo "    // ],\n";
        } else if (++$count < 6) {
            echo "    [\n";
            echo "        'class'=>'\kartik\grid\DataColumn',\n";
            echo "        'attribute'=>'" . $name . "',\n";
            echo "    ],\n";
        } else {
            echo "    // [\n";
            echo "        // 'class'=>'\kartik\grid\DataColumn',\n";
            echo "        // 'attribute'=>'" . $name . "',\n";
            echo "    // ],\n";
        }
    }
    ?>
    <?php if ($displayHc) : ?>
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'maquan',
            'value' => 'tenquan',
            'filter' => Html::activeDropDownList($searchModel, 'maquan', $dmQuan, $selectOptions + ['id' => 'maquan']),
            'label' => 'Quận'
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'maphuong',
            'value' => 'tenphuong',
            'filter' => $depdrop,
            'label' => 'Phường'
        ],
    <?php endif; ?>
    [
        'class' => 'kartik\grid\ActionColumn',
        'urlCreator' => function($action, $model, $key, $index) {
                return url([$action,'<?=substr($actionParams,1)?>' => $key]);
        },
        'viewOptions'=>[<?= $enableAjax ? "'role'=>'modal-remote'" : "'target'=>'_blank'";?>,'title'=> lang('View'),'data-toggle'=>'tooltip'],
        'updateOptions'=>[<?= $enableAjax ? "'role'=>'modal-remote'" : "'target'=>'_blank'";?>,'title'=> lang('Update'), 'data-toggle'=>'tooltip'],
    ],

];   