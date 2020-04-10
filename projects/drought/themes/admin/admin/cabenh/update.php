<?php

use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model covid\models\Cabenh */

$this->title = Yii::t('app', 'Cập nhật: {name}', [
    'name' => $model->id,
]);
?>
<div class="cabenh-update">
    <?= $this->render('_form', [
        'model' => $model,
        'quanheCha' => $quanheCha,
        'quanheCon' => $quanheCon,
        'modelsDiachi' => $modelsDiachi,
        'chartData' => $chartData
    ]) ?>
</div>