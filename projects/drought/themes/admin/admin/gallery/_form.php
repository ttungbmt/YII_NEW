<?php

use yii\helpers\Html;
use yii\imagine\Image;
use yii\widgets\ActiveForm;
use kartik\widgets\DepDrop;
use ttungbmt\map\Map;

/* @var $this yii\web\View */
/* @var $model drought\models\Gallery */
/* @var $form yii\widgets\ActiveForm */

$this->title = ($model->isNewRecord ? 'Thêm mới' : 'Cập nhật') . ' Ảnh đầu vào';
?>


<div class="gallery-form">
    <!--    <div id="mapid" style="width:100%; height:500px"></div>-->
    <div class="card">
        <div class="card-body">
            <?php $form = ActiveForm::begin([
                'enableClientValidation' => false,
                'options' => ['enctype' => 'multipart/form-data']
            ]); ?>
            <div class="d-flex">
                <div id="preview-img" style="<?= $model->getUploadUrl() ? 'margin-right: 20px' : '' ?>">
                    <img src="<?= $model->getThumbUploadUrl()?>" alt="">
                </div>
                <div style="flex-grow: 1;">
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
                </div>
            </div>


            <?php if (!request()->isAjax): ?>
                <div class="form-group text-right mt-2">
                    <?php if ($model->getUploadPath()): ?>
                        <?= Html::a('Tải ảnh', $model->getUploadUrl(), ['class' => 'btn btn-info', 'download']) ?>
                    <?php endif; ?>

                    <?= Html::submitButton($model->isNewRecord ? lang('Create') : lang('Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>
            <?php endif; ?>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<?php

?>
<?=$this->render('_raster_info', ['model' => $model])?>

<?php
$this->registerJsFile('http://seikichi.github.io/tiff.js/tiff.min.js');
?>
<script>
    //let geoTIFFFile = `<?//=$model->getUploadUrl()?>//`
    //$(function () {
    //    if (geoTIFFFile) {
    //        let xhr = new XMLHttpRequest();
    //        xhr.responseType = 'arraybuffer';
    //        xhr.open('GET', geoTIFFFile);
    //        xhr.onload = function (e) {
    //            let tiff = new Tiff({buffer: xhr.response});
    //            let canvas = tiff.toCanvas();
    //            $('#preview-img').append(canvas)
    //        };
    //        xhr.send();
    //    }
    //
    //})
</script>
