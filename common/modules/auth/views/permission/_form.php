<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $model->titlePage('Phân quyền truy cập')
?>
<div class="card">
    <div class="card-body">
        <div class="auth-item-form">

            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label('Tên quyền truy cập') ?>

            <?= $form->field($model, 'description')->textarea(['rows' => 2]) ?>

            <div class="form-group pull-right">
                <?= Html::submitButton($model->isNewRecord ? lang('Create') : lang('Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>

    </div>
</div>

