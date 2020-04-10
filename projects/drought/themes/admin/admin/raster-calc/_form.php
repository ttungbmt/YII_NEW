<?php

use drought\models\Gallery;
use kartik\widgets\Select2;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\imagine\Image;
use yii\widgets\ActiveForm;
use kartik\widgets\DepDrop;
use ttungbmt\map\Map;

/* @var $this yii\web\View */
/* @var $model drought\models\Gallery */
/* @var $form yii\widgets\ActiveForm */

$this->title = ($model->isNewRecord ? 'Thêm mới' : 'Cập nhật') . ' Xử lý ảnh';
$items = Gallery::find()->andWhere(['type' => 1])->pluck('code', 'id');
if($model->bands && is_string($model->bands)) {
    $model->bands = array_filter(explode(',', $model->bands));
}
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
    <div class="card">
        <div class="card-body">
            <?php $form = ActiveForm::begin([
                'enableClientValidation' => false,
                'options' => ['enctype' => 'multipart/form-data']
            ]); ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="d-flex">
                        <h6 class="font-weight-semibold">Raster Bands</h6>
                        <?= Html::button('+', [
                            'class' => 'ml-2 btn badge bg-blue-400 align-self-start',
                            'data-toggle'=> 'modal',
                            'data-target'=> '#modal-bands',
                        ]) ?>
                    </div>

                    <div>
                        <?= $form->field($model, 'bands_str')->dropDownList([], ['multiple' => true, 'id' => 'box-band'])->label(false) ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <h6 class="font-weight-semibold">Result Layer</h6>
                    <?= $form->field($model, 'name')->label('Output layer name') ?>
                </div>
            </div>
            <h6 class="font-weight-semibold">Operators</h6>
            <div>
                <button type="button" class="btn btn-opr" @click="addOpr('+')">+</button>
                <button type="button" class="btn btn-opr" @click="addOpr('sqrt')">sqrt</button>
                <button type="button" class="btn btn-opr" @click="addOpr('cos')">cos</button>
                <button type="button" class="btn btn-opr" @click="addOpr('sin')">sin</button>
                <button type="button" class="btn btn-opr" @click="addOpr('tan')">tan</button>
                <button type="button" class="btn btn-opr" @click="addOpr('log10')">log10</button>
                <button type="button" class="btn btn-opr" @click="addOpr('(')">(</button>
                <button type="button" class="btn btn-opr" @click="addOpr('-')">-</button>
                <button type="button" class="btn btn-opr" @click="addOpr('/')">/</button>
                <button type="button" class="btn btn-opr" @click="addOpr('^')">^</button>
                <button type="button" class="btn btn-opr" @click="addOpr('acos')">acos</button>
                <button type="button" class="btn btn-opr" @click="addOpr('asin')">asin</button>
                <button type="button" class="btn btn-opr" @click="addOpr('atan')">atan</button>
                <button type="button" class="btn btn-opr" @click="addOpr('ln')">ln</button>
                <button type="button" class="btn btn-opr" @click="addOpr(')')">)</button>
                <button type="button" class="btn btn-opr" @click="addOpr('<')"><</button>
                <button type="button" class="btn btn-opr" @click="addOpr('=')">=</button>
                <button type="button" class="btn btn-opr" @click="addOpr('!=')">!=</button>
                <button type="button" class="btn btn-opr" @click="addOpr('<=')"><=</button>
                <button type="button" class="btn btn-opr" @click="addOpr('>=')">>=</button>
                <button type="button" class="btn btn-opr" @click="addOpr('AND')">AND</button>
                <button type="button" class="btn btn-opr" @click="addOpr('OR')">OR</button>
                <button type="button" class="btn btn-opr" @click="addOpr('abs')">abs</button>
                <button type="button" class="btn btn-opr" @click="addOpr('min')">min</button>
                <button type="button" class="btn btn-opr" @click="addOpr('max')">max</button>
            </div>


            <h6 class="font-weight-semibold mt-2">Raster Calculator Expression</h6>
            <?= $form->field($model, 'expr')->textarea(['rows' => 10, 'v-model' => 'expr'])->label(false) ?>


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
                <?=$form->field($model, 'bands')->widget(Select2::classname(), [
                    'data' => $items,
                    'options' => ['placeholder' => 'Chọn ảnh ...', 'multiple' => true],
                    'pluginOptions' => [
                        'tags' => true,
                    ],
                    'pluginEvents' => [
                        "change" => "vm.onChangeBand",
                    ]
                ])?>
            </div>
            <?php Modal::end(); ?>

            <?php ActiveForm::end(); ?>
        </div>
    </div>


</div>

<script>
    let vm = new Vue({
        el: '#vue-app',
        data: {
            bands_arr: <?=json_encode($items)?>,
            selected: <?=json_encode($model->bands)?>,
            expr: '<?=$model->expr?>',
        },
        mounted() {
            this.updateBoxBand()



        },
        computed: {
            bands(){
                return _.map(this.selected).join(',')
            }
        },
        methods: {
            addOpr(v){
                this.expr += v
            },
            onChangeBand(e) {
                let ids = $(e.target).select2('data').map(v => parseInt(v.id))
                this.selected = ids
                this.updateBoxBand()
            },

            updateBoxBand(){
                let self = this
                $(this.$el).find('#box-band').html(this.selected.map(v => `<option value="${v}">${this.bands_arr[v]}</option>`).join(''))

                $('#box-band option').dblclick(function() {
                    let id = $(this).val()
                    self.expr += `[${self.bands_arr[id]}]`
                });
            }
        }
    })
</script>
