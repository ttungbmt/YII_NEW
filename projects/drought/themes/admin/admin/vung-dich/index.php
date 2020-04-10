<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel covid\models\search\VungDichSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Vùng dịch';
?>
<div class="vung-dich-index">
    <?php Pjax::begin(); ?>

    <p>
        <?= Html::a('Thêm mới', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns'      => require(__DIR__ . '/_columns.php'),
        'panel' => [
            'type' => 'primary',
            'heading' => 'Danh sách Vùng dịch'
        ]
    ]); ?>
    <?php Pjax::end(); ?>
</div>
