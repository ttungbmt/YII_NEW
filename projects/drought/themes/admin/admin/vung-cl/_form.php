<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DepDrop;
/* @var $this yii\web\View */
/* @var $model covid\models\VungCl */
/* @var $form yii\widgets\ActiveForm */
$maquan = $model->maquan;
?>
<div class="card">
    <div class="card-body">
        <div class="vung-cl-form">
            <?php $form = ActiveForm::begin(); ?>
            <div class="row">
                <div class="col-md-3">
                    <?= $form->field($model, 'maquan')->dropDownList(api('/dm/quan'), [
                        'prompt'  => 'Chọn quận huyện...',
                        'id'      => 'drop-quan',
                    ]) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'maphuong')->widget(DepDrop::className(), [
                        'options'       => ['prompt' => 'Chọn phường...'],
                        'pluginOptions' => [
                            'depends'      => ['drop-quan'],
                            'url'          => url(['/api/dm/phuong']),
                            'initialize'   => $maquan == true,
                            'placeholder'  => 'Chọn phường...',
                            'ajaxSettings' => ['data' => ['value' => $model->maphuong]],
                        ],
                    ]) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'khupho')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'to_dp')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'kn_dapung')->textInput(['type' => 'number']) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'kn_dieutri')->textInput(['type' => 'number']) ?>
                </div>
            </div>
            <?= $form->field($model, 'dientich')->textInput() ?>

            <div class="form-group">
                <?= Html::submitButton('Lưu', ['class' => 'btn btn-success']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
