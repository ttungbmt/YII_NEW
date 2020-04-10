<?php
use softark\duallistbox\DualListbox;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $model->titlePage('Phân quyền người dùng');
$keys = array_keys(auth()->getPermissions());
$items = array_combine($keys, $keys);
?>
<div class="card">
    <div class="card-body">
        <div class="auth-item-form">

            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label('Tên phân quyền') ?>

            <?= $form->field($model, 'description')->textarea(['rows' => 2]) ?>

            <?= $form->field($model, 'permissions')->dropDownList($items, ['multiple' => true]) ?>
            <?php //echo $form->field($model, 'permissions')->widget(DualListbox::className(), ['items' => $list,]) ?>

            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? lang('Create') : lang('Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>

    </div>
</div>

