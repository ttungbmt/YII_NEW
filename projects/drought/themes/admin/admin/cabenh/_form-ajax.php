<?php

use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin(['action' => 'create-ajax', 'id' => 'form-quanhe']); ?>
<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'ma_qg')->textInput() ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'tuoi')->textInput() ?>
    </div>
    <div class="col-md-12">
        <?= $form->field($model, 'hoten')->textInput() ?>
    </div>
</div>
<?php ActiveForm::end(); ?>