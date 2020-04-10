<?php

use covid\models\Cabenh;

/* @var $this yii\web\View */
/* @var $model covid\models\VungDich */

$this->title = 'Thêm mới';
// $ids = collect(explode(',', request('cabenh_ids')))->map(function ($v) {
//     return (int) trim($v);
// })->all();
// $cabenhs = Cabenh::find()->whereIn($ids)->all();
?>
<div class="vung-dich-create">
    <?= $this->render('_form', [
        'model' => $model,
        'vungdichCabenhModel' => $vungdichCabenhModel
    ]) ?>

</div>