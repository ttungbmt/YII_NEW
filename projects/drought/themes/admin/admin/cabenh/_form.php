<?php

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model covid\models\Cabenh */
/* @var $form yii\widgets\ActiveForm */

$dm_hinhthuc_cl = [
    1 => 'Cấp 1',
    2 => 'Cấp 2',
    3 => 'Cấp 3',
];
$maquan = $model->maquan;
$dm_phanloai = api('dm_phanloai');
$dm_tinh = api('dm/tinh');
$dm_yesno = [
    1 => 'Có',
    2 => 'Không',
];

$ketqua_xn = [
    1 => 'ÂM TÍNH',
    2 => 'DƯƠNG TÍNH',
    3 => 'CHỜ',
];
$dm_tinh = api('dm/tinh');
$dc = new \covid\models\Diachi();
$noi_dcl = $model->getNoiDcl();
if ($noi_dcl ==  null) {
    $noi_dcl = $dc;
}

$dm_noi_ph = api('dm_noi_ph');
$diachis = array_map(function ($model) {
    return $model->toArray();
}, $model->diachis);

$chartData = isset($chartData) ? $chartData : []
?>
<style>
    .box-uutien .form-group,
    .control-label,
    h5,
    label {
        margin-bottom: 0;
    }

    .select2-selection--multiple {
        border: 1px solid #ddd;
        border-width: 1px 0;
        border-top-color: transparent;
    }
</style>
<div id="vue-app">
    <div class="card">
        <div id="chart-tree"></div>
    </div>

    <div class="card">
        <div class="card-body">
            <?php $form = ActiveForm::begin(); ?>
            <h2><span class="badge badge-light badge-striped badge-striped-left border-left-primary">1. Thông tin ca bệnh</span></h2>
            <div class="row">
                <div class="col-md-3">
                    <?= $form->field($model, 'ma_qg')->textInput() ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'ma_hcm')->textInput() ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'ngaycongbo')->widget(\kartik\widgets\DatePicker::className(), [
                        'options' => ['placeholder' => 'DD/MM/YYYY'],
                    ]) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'phanloai')->dropDownList($dm_phanloai, ['prompt' => 'Chọn...']) ?>
                </div>
            </div>



            <div class="row">
                <div class="col-md-3">
                    <?= $form->field($model, 'hoten')->textInput() ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'tuoi')->textInput(['type' => 'number']) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'ngaysinh')->widget(\kartik\widgets\DatePicker::className()) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'gioitinh')->radioList([1 => 'Nam', 2 => 'Nữ']) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <?= $form->field($model, 'dienthoai')->textInput() ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'email')->textInput() ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'noilamviec')->textInput() ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <?= $form->field($model, 'quoctich')->textInput() ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'quoctich_khac')->textInput() ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'sohochieu')->textInput() ?>
                </div>

            </div>

            <h2><span class="badge badge-light badge-striped badge-striped-left border-left-primary">2. Hành trình</span></h2>

            <div class="row">
                <div class="col-md-3">
                    <?= $form->field($model, 'phtien_nc')->textInput() ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'phtien_sh')->textInput() ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'soghe')->textInput() ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'machuyenbay')->textInput() ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'khoihanh_dc')->textInput() ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'ngaykhoihanh')->widget(\kartik\widgets\DatePicker::className(), [
                        'options' => ['placeholder' => 'DD/MM/YYYY'],
                    ]) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'ngaynhapcanh')->widget(\kartik\widgets\DatePicker::className(), [
                        'options' => ['placeholder' => 'DD/MM/YYYY'],
                    ]) ?>
                </div>
            </div>

            <h2><span class="badge badge-light badge-striped badge-striped-left border-left-primary">3. Dịch tễ</span></h2>

            <v-diachi :model="m"></v-diachi>

            <div class="mt-2">
                <?= $form->field($model, 'yeuto_dt')->textInput() ?>
                <?= $form->field($model, 'tiensu_dt_khac')->textarea(['rows' => 5]) ?>
            </div>
            <h4><span class="badge badge-light badge-striped badge-striped-left border-left-primary mt-2">Điều trị</span></h4>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'bv_dt')->textInput() ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'ngayvaovien')->widget(\kartik\widgets\DatePicker::className(), [
                        'options' => ['placeholder' => 'DD/MM/YYYY'],
                    ]) ?>
                </div>

            </div>

            <h4><span class="badge badge-light badge-striped badge-striped-left border-left-primary">Cách ly</span></h4>
            <div class="row">
                <div class="col-md-3">
                    <?= $form->field($model, 'noi_cl')->textInput() ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'ngaybatdau_cl')->widget(\kartik\widgets\DatePicker::className(), [
                        'options' => ['placeholder' => 'DD/MM/YYYY'],
                    ]) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'ngayketthuc_td')->widget(\kartik\widgets\DatePicker::className(), [
                        'options' => ['placeholder' => 'DD/MM/YYYY'],
                    ]) ?>
                </div>
            </div>
            <h4><span class="badge badge-light badge-striped badge-striped-left border-left-primary">Xét nghiệm</span></h4>
            <div class="row">
                <div class="col-md-3">
                    <?= $form->field($model, 'laymau_xn')->radioList($dm_yesno) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'ngaylaymau')->widget(\kartik\widgets\DatePicker::className(), [
                        'options' => ['placeholder' => 'DD/MM/YYYY'],
                    ]) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'noilaymau')->textInput() ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'ketqua_xn')->dropDownList($ketqua_xn, ['prompt' => 'Chọn...']) ?>
                </div>
            </div>
            <!-- Form Quan he -->

            <div class="form-group text-right">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<?php require(__DIR__ . '/_form_diachi_script.php') ?>
<script>
    window.PageStore = {
        model: <?= json_encode($model->toArray()) ?>,
        diachis: <?= json_encode($diachis) ?>,
        chart: <?= json_encode($chartData) ?>,
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/react@16.13.1/umd/react.development.js"></script>
<script src="https://cdn.jsdelivr.net/npm/react-dom@16.13.1/umd/react-dom.development.js"></script>
<script src="//www.amcharts.com/lib/4/core.js"></script>
<script src="//www.amcharts.com/lib/4/charts.js"></script>
<script src="//www.amcharts.com/lib/4/themes/animated.js"></script>
<script src="//www.amcharts.com/lib/4/plugins/forceDirected.js">
    <?php
    $this->registerJsFile('/projects/covid/covid-app/dist/pages/cabenh.js', [
        'position' => View::POS_END,
        'depends' => [common\assets\AppPluginAsset::className()]
    ])
    ?>