<?php

use dosamigos\fileupload\FileUpload;

$this->title = 'File Manager';

use kartik\widgets\FileInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="card">
    <div class="card-body">
        <?php $form = ActiveForm::begin([
            'enableClientValidation' => false,
            'options' => ['enctype' => 'multipart/form-data']
        ]); ?>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'code')->textInput() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'date')->widget(\kartik\widgets\DatePicker::className(), [
                    'options' => ['placeholder' => 'DD/MM/YYY']
                ]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'image')->fileInput() ?>
            </div>
        </div>


        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>

