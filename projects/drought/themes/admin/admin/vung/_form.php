<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model covid\models\Vung */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vung-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'diachi')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'maphuong')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'maquan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'geom')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
