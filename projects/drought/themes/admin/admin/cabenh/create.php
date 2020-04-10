<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model covid\models\Cabenh */

$this->title = Yii::t('app', 'Thêm mới');
?>
<div class="cabenh-create">
    <?= $this->render('_form', [
        'model' => $model,
        'quanheCha' => $quanheCha,
        'quanheCon' => $quanheCon,
        'modelsDiachi' => $modelsDiachi
    ]) ?>
</div>