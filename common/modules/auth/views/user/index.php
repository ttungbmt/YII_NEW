<?php

use kartik\export\ExportMenu;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;

$gridColumns = require(__DIR__ . '/_columns.php');

$this->title = 'Quản lý Người dùng';
//echo ExportMenu::widget([
//    'dataProvider' => $dataProvider,
//    'columns' => $gridColumns
//]);

CrudAsset::register($this);
?>
<div class="user-index">
    <div id="ajaxCrudDatatable">
        <?= GridView::widget([
            'id' => 'crud-datatable',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax' => true,
            'columns' => $gridColumns,
            'toolbar' => [
                ['content' =>
                    Html::a('Thêm mới', ['create'],
                        ['title' => 'Thêm mới người dùng', 'class' => 'btn btn-default']) .
                    Html::a('<i class="icon-reload-alt"></i>', [''],
                        ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Reset Grid']) .
                    '{toggleData}' .
                    '{export}'
                ],
            ],
            'striped' => true,
            'condensed' => true,
            'responsive' => true,
            'panel' => [
                'type' => 'primary',
                'heading' => ' Danh sách Người dùng',
            ]
        ]) ?>
    </div>
</div>
<?php Modal::begin([
    "id" => "ajaxCrudModal",
    "footer" => "",// always need it for jquery plugin
]) ?>
<?php Modal::end(); ?>
