<?php

use covid\models\Cabenh;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model covid\models\VungDich */

$this->title = 'Cập nhật: ' . $model->id;
// $ids = collect(explode(',', request('cabenh_ids')))->map(function ($v) {
//     return (int) trim($v);
// })->all();
// $cabenhs = Cabenh::find()->whereIn($ids)->all();
?>
<div class="vung-dich-update">
    <?= $this->render('_form', [
        'model' => $model,
        'vungdichCabenhModel' => $vungdichCabenhModel
    ]) ?>

</div>