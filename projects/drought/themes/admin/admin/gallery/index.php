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
?>
<div class="gallery-index">
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
            ]
        ]) ?>
    </div>
</div>
<?php Modal::begin([
    "id" => "ajaxCrudModal",
    "footer" => "",// always need it for jquery plugin
]) ?>
<?php Modal::end(); ?>





