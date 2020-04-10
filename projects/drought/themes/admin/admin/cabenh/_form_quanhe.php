<?php

use kartik\select2\Select2;
use yii\web\JsExpression;


$addCabenhCha =  <<< JS
function (e) {
    vueapp.addCabenhCha(e.params.data.id);
}
JS;
$addCabenhCon =  <<< JS
function (e) {
    vueapp.addCabenhCon(e.params.data.id);
}
JS;

$removeCabenhCha =  <<< JS
function (e) {
    vueapp.removeCabenhCha(e.params.data.id);
}
JS;
$removeCabenhCon =  <<< JS
function (e) {
    vueapp.removeCabenhCon(e.params.data.id);
}
JS;

?>
<div id="app">
    <h2><span class="badge badge-light badge-striped badge-striped-left border-left-primary">4. Quan hệ lây nhiễm</span></h2>

    <div class="form-group">
        <label>Quan hệ cha</label>
        <a href="#" class="btn btn-icon btn-sm btn-primary ml-2" title="Thêm ca bệnh" data-toggle="modal" data-target="#modalCha" @click="loadFormCha"><i class="icon-plus2"></i></a>

        <?= Select2::widget([
            'name' => 'CabenhQuanHeForm[quanhe_cha]',
            'id' => 'select-quanhe-cha',
            'value' => $quanheCha,
            'options' => [
                'placeholder' => 'Chọn ca bệnh ...',
                'multiple' => true
            ],
            'pluginOptions' => [
                'minimumInputLength' => 1,
                'ajax' => [
                    'url' => 'get-cabenhs',
                    'dataType' => 'json',
                ],
            ],
            'pluginEvents' => [
                "select2:select" => new JsExpression($addCabenhCha),
                "select2:unselect" => new JsExpression($removeCabenhCha)
            ]
        ]); ?>

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
                    <tr v-for="(cb, i) in cabenhCha">
                        <td>{{i+1}}</td>
                        <td>{{cb.hoten}}</td>
                        <td>{{cb.ma_qg}}</td>
                        <td>{{cb.tuoi}}</td>
                        <td>{{cb.diachi}}</td>
                    </tr>

                </tbody>
            </table>
        </div>

    </div>
    <div class="form-group">
        <label>Quan hệ con</label>
        <a href="#" class="btn btn-icon btn-primary btn-sm ml-2" title="Thêm ca bệnh" @click="loadFormCon" data-toggle="modal" data-target="#modalCon"><i class="icon-plus2"></i></a>

        <?= Select2::widget([
            'name' => 'CabenhQuanHeForm[quanhe_con]',
            'id' => 'select-quanhe-con',
            'value' => $quanheCon,
            'options' => [
                'placeholder' => 'Chọn ca bệnh ...',
                'multiple' => true
            ],
            'pluginOptions' => [
                'minimumInputLength' => 1,
                'ajax' => [
                    'url' => 'get-cabenhs',
                    'dataType' => 'json',
                ],
            ],
            'pluginEvents' => [
                "select2:select" => new JsExpression($addCabenhCon),
                "select2:unselect" => new JsExpression($removeCabenhCon)
            ]
        ]); ?>
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
                    <tr v-for="(cb, i) in cabenhCon">
                        <td>{{i+1}}</td>
                        <td>{{cb.hoten}}</td>
                        <td>{{cb.ma_qg}}</td>
                        <td>{{cb.tuoi}}</td>
                        <td>{{cb.diachi}}</td>
                    </tr>

                </tbody>
            </table>
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
                    <button type="button" class="btn btn-success" @click="submitFormCha">Lưu</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalCon" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                    <button type="button" class="btn btn-success" @click="submitFormCon">Lưu</button>
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
                cabenhCha: [],
                cabenhCon: [],
                url: 'get-cabenh',
                showModalCha: true
            }
        },
        mounted() {
            const initCabenhCha = JSON.parse('<?= json_encode($quanheCha) ?>');
            const initCabenhCon = JSON.parse('<?= json_encode($quanheCon) ?>');
            for (const ma_qg of initCabenhCha) {
                this.addCabenhCha(ma_qg);
            }
            for (const ma_qg of initCabenhCon) {
                this.addCabenhCon(ma_qg)
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
            addCabenhCha(ma_qg) {
                this.getData(this.url, {
                        ma_qg: ma_qg
                    },
                    (result) => {
                        if (result.cabenh) {
                            this.cabenhCha.push(result.cabenh);
                        }
                    }
                )
            },
            removeCabenhCha(ma_qg) {
                this.cabenhCha = this.cabenhCha.filter(cb => cb.ma_qg != ma_qg);
            },
            addCabenhCon(ma_qg) {
                this.getData(this.url, {
                        ma_qg: ma_qg
                    },
                    (result) => {
                        if (result.cabenh) {
                            this.cabenhCon.push(result.cabenh);
                        }
                    }
                )
            },
            removeCabenhCon(ma_qg) {
                this.cabenhCon = this.cabenhCon.filter(cb => cb.ma_qg != ma_qg);
            },
            submitFormCha() {
                const url = 'create-ajax';
                const modalId = '#modalCha';
                const data = $(modalId + ' form').serialize();
                const $select = $('#select-quanhe-cha');
                this.getData(url, data, (result) => {
                    if (!result.cabenh) {
                        $(modalId + ' .modal-form').html(result);
                        return;
                    }

                    const maQg = result.cabenh.ma_qg;
                    this.pushValueToSelect($select, maQg, maQg)
                    $(modalId).modal('hide');

                    if (result.cabenh.id) {
                        this.cabenhCha.push(result.cabenh);
                    }
                }, 'POST')
            },
            submitFormCon() {
                const url = 'create-ajax';
                const modalId = '#modalCon';
                const data = $(modalId + ' form').serialize();
                const $select = $('#select-quanhe-con');
                this.getData(url, data, (result) => {
                    if (!result.cabenh) {
                        $(modalId + ' .modal-form').html(result);
                        return;
                    }
                    const maQg = result.cabenh.ma_qg;
                    this.pushValueToSelect($select, maQg, maQg)
                    $(modalId).modal('hide');

                    if (result.cabenh.id) {
                        this.cabenhCon.push(result.cabenh);
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
            loadFormCha() {
                const url = 'create-ajax';
                this.getData(url, {}, (html) => {
                    $('#modalCha .modal-form').html(html);
                });
            },
            loadFormCon() {
                const url = 'create-ajax';
                this.getData(url, {}, (html) => {
                    $('#modalCon .modal-form').html(html);
                });
            }
        },
    })
</script>