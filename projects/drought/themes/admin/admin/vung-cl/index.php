<?php
use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel covid\models\search\VungClSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Vùng cách ly';
?>
<div class="vung-cl-index">
    <?php Pjax::begin(); ?>
    <p>
        <?= Html::a('Thêm mới', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns'      => require(__DIR__ . '/_columns.php'),
        'panel' => [
            'type' => 'primary',
            'heading' => 'Danh sách Vùng cách ly'
        ]
    ]); ?>
    <?php Pjax::end(); ?>
</div>
