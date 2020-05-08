<?php

use kartik\widgets\Select2;
use ttungbmt\db\Query;
use yii\db\Expression;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model drought\models\Gallery */
/* @var $form yii\widgets\ActiveForm */

$this->title = ($model->isNewRecord ? 'Thêm mới' : 'Cập nhật') . ' Ảnh đầu vào';
$dm_folder = api('dm_folder');
$imgs = \drought\models\Gallery::find()->andFilterWhere(['type' => 1, 'folder' => 'ndvi'])->pluck('code', 'id');
$layers = $model->getViewTb();

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
                    <img src="<?= $model->getThumbUploadUrl() ?>" alt="">
                </div>
                <div style="flex-grow: 1;">
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md-3">
                            <?= $form->field($model, 'code')->textInput() ?>
                        </div>
                        <div class="col-md-3">
                            <?= $form->field($model, 'folder')->dropDownList($dm_folder, ['prompt' => 'Chọn thư mục...']) ?>
                        </div>
                    </div>
                    <?= $form->field($model, 'image')->fileInput() ?>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'date')->widget(\kartik\date\DatePicker::className(), ['options' => ['placeholder' => 'DD/MM/YYY']])->label('Ngày') ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'year')->textInput(['type' => 'number']) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'dimension')->textInput()->label('Chuyển đổi kích thước ảnh') ?>
                            <div class="text-muted">Vd: 370 330</div>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'resampling_img')
                                ->widget(Select2::classname(), [
                                    'data' => $imgs,
                                    'options' => ['placeholder' => 'Chọn hình ảnh ...'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ])
                                ->label('Ảnh tham chiếu') ?>
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

<div id="vue-app">
    <?php if ($model->tiffExists()): ?>
        <?= $this->render('../raster-calc/_reclass_form', [
            'model' => $model, 'name' => 'm_' . $model->code,
             'redirectUrl' => '/admin/gallery/update?id='.$model->id
        ]) ?>
    <?php endif; ?>
</div>


<?= $this->render('_raster_info', ['model' => $model]) ?>
<?php
$this->registerJsFile('http://seikichi.github.io/tiff.js/tiff.min.js');
$this->registerJsFile(mix('raster-calc/index.js', 'projects/drought/lerna/pages/dist'));
$this->registerJsVar('globalStore', [
    'geoserver' => [
        'layers' => $layers ? "drought:{$layers}" : '',
    ],
    'form' => [
        'expr' => $model->expr ? $model->expr : '',
        'bands' => $model->bands ? $model->bands : []
    ],
    'symForm' => [
        'mode' => 'nb',
        'nbClass' => 5,
        'legendFormat' => '1% - 2%',
        'symbols' => $model->symbology ? $model->symbology : [],
        'invertColorRamp' => [1],
    ],
    'existFile' => $model->tiffExists() ? 1 : 0,
    'bands_arr' => [],
    'selected' => $model->bands ? $model->bands : [],
    'gradientSelected' => 'OrRd',
    'statData' => $statData,
    'srcMap' => '',
])
?>
