<?php

use drought\models\Gallery;
use drought\support\Mix;
use kartik\widgets\Select2;
use ttungbmt\db\Query;
use ttungbmt\support\facades\Http;
use yii\bootstrap\Modal;
use yii\db\Expression;
use yii\helpers\Html;
use yii\imagine\Image;
use yii\widgets\ActiveForm;
use kartik\widgets\DepDrop;
use ttungbmt\map\Map;

/* @var $this yii\web\View */
/* @var $model drought\models\Gallery */
/* @var $form yii\widgets\ActiveForm */

$this->title = ($model->isNewRecord ? 'Thêm mới' : 'Cập nhật') . ' Tính toán CDI';
$items = Gallery::find()->andWhere(['type' => 1])->pluck('code', 'id');
if ($model->bands && is_string($model->bands)) {
    $model->bands = array_filter(explode(',', $model->bands));
}
$img_url = $model->getFileCalcUrl();
$layers = 'm_' . $model->code;
$statData = $model->tiffExists() ? (new Query())->select(new Expression('DISTINCT val::int'))->from($layers)->pluck('val') : [];


?>
<style>
    .btn-opr {
        width: 100px;
        margin-bottom: 3px;
    }

    .select2-selection--multiple {
        border: 2px solid #2196f3;
        padding: 5px 10px;
        border-radius: 5px;
    }
</style>


<div id="vue-app" class="gallery-form">
    <?php if ($model->tiffExists()): ?>
        <div>
            <div class="d-flex">
                <div style="margin-right: 10px;">
                    <div id="preview-tiff"></div>
                </div>
                <?= $this->render('_raster_info', ['model' => $model]) ?>
            </div>
            <hr>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <?php $form = ActiveForm::begin([
                'enableClientValidation' => false,
                'options' => ['enctype' => 'multipart/form-data']
            ]); ?>

            <div class="row d-flex">
                <div style="width: 415px">
                    <div class="d-flex">
                        <h6 class="font-weight-semibold">Raster Bands</h6>
                        <?= Html::button('+', [
                            'class' => 'ml-2 btn badge bg-blue-400 align-self-start',
                            'data-toggle' => 'modal',
                            'data-target' => '#modal-bands',
                        ]) ?>
                    </div>

                    <div>
                        <?= $form->field($model, 'bands_str')->dropDownList([], ['multiple' => true, 'id' => 'box-band'])->label(false) ?>
                    </div>
                </div>
                <div style="flex-grow: 1; padding-left: 20px">
                    <h6 class="font-weight-semibold">Result Layer</h6>
                    <?= $form->field($model, 'name')->label('Output layer name') ?>
                </div>
            </div>
            <v-panel-operator :on-click="addOpr"></v-panel-operator>

            <h6 class="font-weight-semibold mt-2">Raster Calculator Expression</h6>
            <span class="badge badge-light badge-striped badge-striped-left border-left-primary mb-2">CDI = NDVIa * 0.5 +SPI * 0.3333 + LSTa * 0.1667</span>

            <?= $form->field($model, 'expr')->textarea(['rows' => 10, 'v-model' => 'form.expr'])->label(false) ?>

            <?php if (!request()->isAjax): ?>
                <div class="form-group text-right mt-2">
                    <?= Html::submitButton($model->isNewRecord ? lang('Create') : lang('Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>
            <?php endif; ?>



            <?php Modal::begin([
                "id" => "modal-bands",
                'header' => '<h6 class="font-weight-semibold" style="position: absolute" >Thêm ảnh vào danh sách</h6>'
            ]) ?>
            <div>
                <?= $form->field($model, 'bands')->widget(Select2::classname(), [
                    'data' => $items,
                    'options' => ['placeholder' => 'Chọn ảnh ...', 'multiple' => true, 'id' => 'btnChoseImg'],
                    'pluginOptions' => [
                        'tags' => true,
                    ],
                ]) ?>
            </div>
            <?php Modal::end(); ?>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <?php if ($model->tiffExists()): ?>
        <?= $this->render('_reclass_form', ['model' => $model, 'name' => 'm_' . $model->code]) ?>
    <?php endif; ?>


</div>

<?php
$this->registerJsFile('http://seikichi.github.io/tiff.js/tiff.min.js');
$this->registerJsFile(mix('raster-calc/index.js', 'projects/drought/lerna/pages/dist'));

$this->registerJsVar('globalStore', [
    'geoserver' => [
        'layers' => $layers ? "drought:{$layers}" : '',
    ],
    'form' => ['expr' => $model->expr],
    'existFile' => $model->tiffExists() ? 1 : 0,
    'bands_arr' => $items,
    'selected' => $model->bands,
    'tiffUrl' => $model->tiffExists() ? $img_url : '',
    'gradientSelected' => '',
    'statData' => $statData,
    'srcMap' => '',
])
?>

