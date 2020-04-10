<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel covid\models\search\CabenhSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Ca bệnh Covid');
?>
<div class="cabenh-index">
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <div class="d-flex mb-2 justify-content-between">
        <div class="btn-group" role="group" aria-label="Basic example">
            <a class="btn btn-danger" href="<?=url(['', 'form[phanloai_index]' => 1])?>">Ca dương tính</a>
            <a class="btn btn-info" href="<?=url(['', 'form[phanloai_index]' => 2])?>">Nghi nhiễm</a>
        </div>
        <?= Html::a(Yii::t('app', 'Thêm mới'), ['create'], ['class' => 'btn btn-success']) ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => require(__DIR__ . '/_columns.php'),
        'panel' => [
            'type' => 'primary',
            'heading' => ' Danh sách Ca bệnh'
        ]
    ]); ?>
    <?php Pjax::end(); ?>
</div>
