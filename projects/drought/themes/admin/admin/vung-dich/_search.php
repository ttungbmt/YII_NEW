<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model covid\models\search\VungDichSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vung-dich-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'diachi') ?>

    <?= $form->field($model, 'maphuong') ?>

    <?= $form->field($model, 'maquan') ?>

    <?= $form->field($model, 'khupho') ?>

    <?php // echo $form->field($model, 'to_dp') ?>

    <?php // echo $form->field($model, 'geom') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
