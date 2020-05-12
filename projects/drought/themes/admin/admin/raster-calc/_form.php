<?php

use drought\models\Gallery;
use kartik\widgets\Select2;
use ttungbmt\db\Query;
use yii\bootstrap\Modal;
use yii\db\Expression;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\imagine\Image;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model drought\models\Gallery */
/* @var $form yii\widgets\ActiveForm */

$this->title = ($model->isNewRecord ? 'Thêm mới' : 'Cập nhật') . ' Tính toán CDI';
$year = $model->isNewRecord ? request()->get('year') : null;
$items = Gallery::find()->andFilterWhere(['type' => 1, 'year' => request()->get('year')])->pluck('code', 'id');
if ($model->bands && is_string($model->bands)) {
    $model->bands = array_filter(explode(',', $model->bands));
}
$img_url = $model->getFileCalcUrl();
$dm_year = api('dm/year?type=1');
$year = request()->get('year', '');
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

    .table-symbology td {
        border: none;
        padding: 5px 0
    }

    .table-symbology .form-group {
        margin: 0
    }

    .custom-checkbox {
        padding: 0
    }
</style>


<div id="vue-app" class="gallery-form">

    <?php if ($model->tiffExists()): ?>
        <div>
            <div class="d-flex">
                <div style="margin-right: 10px;">
                    <div id="preview-tiff"></div>
                    <div class="text-center">
                        <?= Html::a('Tải ảnh', $model->getFileCalcUrl(), ['class' => 'btn btn-info', 'download' => true]) ?>
                    </div>
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
                        <h6 class="font-weight-semibold">Ảnh đầu vào</h6>
                        <?= Html::button('+', [
                            'class' => 'ml-2 btn badge bg-blue-400 align-self-start',
                            'data-toggle' => 'modal',
                            'data-target' => '#modal-bands',
                        ]) ?>

                        <?= Html::dropDownList('redirectYear', request()->get('year'), $dm_year, ['id' => 'drop-year', 'class' => 'form-control', 'prompt' => 'Chọn năm ...', 'style' => 'width: 100px;margin: -10px 0 0 10px;']) ?>
                    </div>

                    <div>
                        <?= $form->field($model, 'bands_str')->dropDownList([], ['multiple' => true, 'id' => 'box-band'])->label(false) ?>
                    </div>
                </div>
                <div style="flex-grow: 1; padding-left: 20px">
                    <h6 class="font-weight-semibold">Kết quả</h6>
                    <?= $form->field($model, 'image')->fileInput()->label('Upload Kết quả tính toán') ?>
                    <?= $form->field($model, 'date')->widget(\kartik\date\DatePicker::className(), ['options' => ['placeholder' => 'DD/MM/YYY']])->label('Ngày tính toán') ?>
                    <?= $form->field($model, 'name')->textInput()->label('Tên file xuất') ?>
                </div>
            </div>
            <v-panel-operator :on-click="addOpr"></v-panel-operator>

            <h6 class="font-weight-semibold mt-2">Công thức tính CDI</h6>
            <span class="badge badge-light badge-striped badge-striped-left border-left-primary mb-2">NDVIa * 0.5 +SPI * 0.3333 + LSTa * 0.1667</span>

            <?= $form->field($model, 'expr')->textarea(['rows' => 10, 'v-model' => 'form.expr'])->label(false) ?>

            <?php if (!request()->isAjax): ?>
                <div class="form-group mt-2">
                    <?= Html::submitButton('Calculate', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
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
        <?= $this->render('_reclass_form', [
            'model' => $model, 'name' => 'm_' . $model->code,
            'redirectUrl' => '/admin/raster-calc/update?id='.$model->id
        ]) ?>
    <?php endif; ?>


</div>

<?php
$this->registerJsFile('http://seikichi.github.io/tiff.js/tiff.min.js');
//$this->registerJsFile(url('/app/libs/vuexy/vuexy.umd.js'));
$this->registerJsFile(mix('raster-calc/index.js', 'projects/drought/lerna/pages/dist'));

$layers = $model->getViewTb();
$statData = $model->tiffExists() ? (new Query())->select(new Expression('DISTINCT val::int'))->from($layers)->pluck('val') : [];

$this->registerJsVar('globalStore', [
    'geoserver' => [
        'layers' => $layers ? "drought:{$layers}" : '',
    ],
    'form' => [
        'expr' => $model->expr ? $model->expr : '',
        'bands' => $model->bands ? $model->bands : []
    ],
    'symForm' => [
        'mode' => 'cdi',
        'nbClass' => 5,
        'legendFormat' => '1% - 2%',
        'symbols' => $model->symbology ? $model->symbology : [],
        'invertColorRamp' => [1],
    ],
    'existFile' => $model->tiffExists() ? 1 : 0,
    'bands_arr' => $items,
    'selected' => $model->bands ? $model->bands : [],
    'tiffUrl' => $model->tiffExists() ? $img_url : '',
    'gradientSelected' => 'OrRd',
    'statData' => $statData,
    'srcMap' => '',
    'inpYear' => '',
    'redirectYear' => $model->isNewRecord ? Url::to(['create']) . '?{year}' : Url::to(['update', 'id' => $model->id]) . '&{year}',
])
?>


