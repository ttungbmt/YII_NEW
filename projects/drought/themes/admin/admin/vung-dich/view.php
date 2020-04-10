<?php

use yii\helpers\Html;
use kartik\detail\DetailView;



$this->title = $model->ten;

\yii\web\YiiAsset::register($this);
?>
<div class="card card-body">


    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'diachi:ntext',
            'maphuong',
            'maquan',
            'khupho',
            'to_dp',
            'geom',
        ],
    ]) ?>

</div>