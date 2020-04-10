<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model covid\models\search\VungClSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vung-cl-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'geom') ?>

    <?= $form->field($model, 'created_at') ?>

    <?= $form->field($model, 'dientich') ?>

    <?= $form->field($model, 'maphuong') ?>

    <?php // echo $form->field($model, 'maquan') ?>

    <?php // echo $form->field($model, 'khupho') ?>

    <?php // echo $form->field($model, 'to_dp') ?>

    <?php // echo $form->field($model, 'kn_dapung') ?>

    <?php // echo $form->field($model, 'kn_dieutri') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
