<?php
use yii\widgets\DetailView;
use drought\support\PgCommand;
$this->title = 'Chi tiết: '.$model->name;
?>

<?php

?>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'image',
        'name',
        'code',
        'date',
    ],
]) ?>
<hr>

<?=$this->render('_raster_info', ['model' => $model])?>
