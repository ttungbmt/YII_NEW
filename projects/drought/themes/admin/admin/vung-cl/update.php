<?php
/* @var $this yii\web\View */
/* @var $model covid\models\VungCl */

$this->title = 'Cập nhật: '. $model->id;
?>
<div class="vung-cl-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
