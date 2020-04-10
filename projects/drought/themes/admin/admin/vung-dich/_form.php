<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DepDrop;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model covid\models\VungDich */
/* @var $form yii\widgets\ActiveForm */

$addCabenh =  <<< JS
function (e) {
    vueapp.addCabenh(e.params.data.id);
}
JS;
$removeCabenh =  <<< JS
function (e) {
    vueapp.removeCabenh(e.params.data.id);
}
JS;
$maquan = $model->maquan;
?>
<div class="card" id="app">
    <div class="card-body">
        <div class="vung-dich-form">

            <?php $form = ActiveForm::begin(); ?>
            <div class="badge badge-light badge-striped badge-striped-left border-left-primary mb-3" style="font-size: 16px">1. Danh sách ca bệnh
            </div>
            <a href="#" class="btn btn-icon btn-sm btn-primary ml-2" title="Thêm ca bệnh" data-toggle="modal" data-target="#modalCha" @click="loadForm"><i class="icon-plus2"></i></a>
            <?= $form->field($vungdichCabenhModel, 'cabenhIds')->widget(Select2::class, [
                'options' => [
                    'placeholder' => 'Chọn ca bệnh ...',
                    'multiple' => true
                ],
                'pluginOptions' => [
                    'minimumInputLength' => 1,
                    'ajax' => [
                        'url' => '/admin/cabenh/get-cabenhs',
                        'dataType' => 'json',
                    ],
                ],
                'pluginEvents' => [
                    "select2:select" => new JsExpression($addCabenh),
                    "select2:unselect" => new JsExpression($removeCabenh)
                ]
            ])->label(false) ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Họ tên</th>
                            <th>Mã QG</th>
                            <th>Tuổi</th>
                            <th>Địa chỉ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(cb, i) in cabenhs">
                            <td>{{i+1}}</td>
                            <td>{{cb.hoten}}</td>
                            <td>{{cb.ma_qg}}</td>
                            <td>{{cb.tuoi}}</td>
                            <td>{{cb.diachi}}</td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <div class="badge badge-light badge-striped badge-striped-left border-left-primary mb-3 mt-3" style="font-size: 16px">2. Thông tin vùng dịch</div>

            <?= $form->field($model, 'diachi')->textInput(['maxlength' => true]) ?>

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

            <div class="form-group">
                <?= Html::submitButton('Lưu', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>

    </div>
    <div class="modal fade" id="modalCha" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Thêm ca bệnh</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body modal-form">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-success" @click="submitForm">Lưu</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script defer>
    const vueapp = new Vue({
        el: '#app',
        data() {
            return {
                cabenhs: [],
                getCabenhUrl: '/admin/cabenh/get-cabenh',
                createAjaxUrl: '/admin/cabenh/create-ajax',
            }
        },
        mounted() {
            const initCabenh = JSON.parse('<?= json_encode($vungdichCabenhModel->cabenhIds) ?>');
            for (const ma_qg of initCabenh) {
                this.addCabenh(ma_qg);
            }

        },
        methods: {
            getData(url, data, callback, method = 'GET') {
                $.ajax({
                    url: url,
                    data: data,
                    method: method,
                    success: callback,
                    error: () => {
                        alert('Đã có lỗi xảy ra, vui lòng thử lại.')
                    }
                })
            },
            addCabenh(ma_qg) {
                this.getData(this.getCabenhUrl, {
                        ma_qg: ma_qg
                    },
                    (result) => {
                        if (result.cabenh) {
                            this.cabenhs.push(result.cabenh);
                        }
                    }
                )
            },
            removeCabenh(ma_qg) {
                this.cabenhs = this.cabenhs.filter(cb => cb.ma_qg != ma_qg);
            },

            submitForm() {
                const modalId = '#modalCha';
                const data = $(modalId + ' form').serialize();
                const $select = $('#vungdichcabenhform-cabenhids');
                this.getData(this.createAjaxUrl, data, (result) => {
                    if (!result.cabenh) {
                        $(modalId + ' .modal-form').html(result);
                        return;
                    }

                    const maQg = result.cabenh.ma_qg;
                    this.pushValueToSelect($select, maQg, maQg)
                    $(modalId).modal('hide');

                    if (result.cabenh.id) {
                        this.cabenhs.push(result.cabenh);
                    }
                }, 'POST')
            },
            pushValueToSelect($select, id, text) {
                var newOption = new Option(text, id, true, true);
                $select.append(newOption).trigger('change');
                let newVal = $select.val();
                newVal.push(id.toString());
                $select.val(newVal).trigger('change');
            },
            loadForm() {
                this.getData(this.createAjaxUrl, {}, (html) => {
                    $('#modalCha .modal-form').html(html);
                });
            },

        },
    })
</script>