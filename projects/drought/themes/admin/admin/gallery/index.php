<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;

/* @var $this yii\web\View */
/* @var $searchModel drought\models\search\Gallery */
/* @var $dataProvider yii\data\ActiveDataProvider */
CrudAsset::register($this);

$this->title = "Ảnh đầu vào";
$dm_year = api('dm/year?type=1');
?>
<div class="gallery-index">
    <?=Html::dropDownList('year', null,  $dm_year, ['id' => 'drop-year', 'class' => 'form-control mb-2', 'prompt' => 'Chọn năm ...'])?>

    <div id="ajaxCrudDatatable">
        <?= GridView::widget([
            'id' => 'crud-datatable',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'filterSelector' => 'select[name="pagination"]',
            'pjax' => true,
            'columns' => require(__DIR__ . '/_columns.php'),
            'toolbar' => [
                ['content' =>
                    Html::a('Thêm mới', ['create'],
                        ['data-pjax' => 0, 'title' => 'Thêm mới Ảnh đầu vào', 'class' => 'btn btn-default',]) .
                    Html::a('<i class="icon-reload-alt"></i>', [''],
                        ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => lang('Reset Grid')]) .
                    '{toggleData}' .
                    '{export}'
                ],
            ],
            'striped' => true,
            'condensed' => true,
            'responsive' => true,
            'panel' => [
                'type' => 'primary',
                'heading' => 'Ảnh đầu vào',
                'before'  =>  Html::tag('div', (
                    Html::a('Tất cả', ['/admin/gallery'], ['class' => 'btn btn-default btn-raised '.(request()->get('folder', 'all') === 'all' ? 'btn-primary': '')]).
                    Html::a('NDVI', ['/admin/gallery', 'folder' => 'ndvi'], ['class' => 'btn btn-default btn-raised '.(request()->get('folder') === 'ndvi' ? 'btn-primary': '')]).
                    Html::a('LST', ['/admin/gallery', 'folder' => 'lst'], ['class' => 'btn btn-default btn-raised '.(request()->get('folder') === 'lst' ? 'btn-primary': '')]).
                    Html::a('SPI', ['/admin/gallery', 'folder' => 'spi'], ['class' => 'btn btn-default btn-raised '.(request()->get('folder') === 'spi' ? 'btn-primary': '')]).
                    Html::a('Khác', ['/admin/gallery', 'folder' => 'khac'], ['class' => 'btn btn-default btn-raised '.(request()->get('folder') === 'khac' ? 'btn-primary': '')])
                ), ['class' => 'btn-group'])
            ]
        ]) ?>
    </div>
</div>
<?php Modal::begin([
    "id" => "ajaxCrudModal",
    "footer" => "",// always need it for jquery plugin
]) ?>
<?php Modal::end(); ?>


<script>
    $(function () {
        $('#drop-year').change(function (e) {
            $.pjax({url: `/admin/gallery?year=`+e.target.value, container: '#crud-datatable-pjax'})
        })
    })
</script>


