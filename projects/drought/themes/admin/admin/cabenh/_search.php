<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model covid\models\search\CabenhSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cabenh-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'hoten') ?>

    <?= $form->field($model, 'tuoi') ?>


    <?= $form->field($model, 'ngaymacbenh') ?>

    <?php // echo $form->field($model, 'ngayphathien') ?>

    <?php // echo $form->field($model, 'geom') ?>

    <?php // echo $form->field($model, 'diachi') ?>

    <?php // echo $form->field($model, 'maquan') ?>

    <?php // echo $form->field($model, 'maphuong') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
