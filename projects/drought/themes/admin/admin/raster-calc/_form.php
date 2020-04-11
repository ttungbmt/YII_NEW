<?php

use drought\models\Gallery;
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
if($model->bands && is_string($model->bands)) {
    $model->bands = array_filter(explode(',', $model->bands));
}
$img_url = $model->getFileCalcUrl();
$layers = 'm_'.$model->code;
$statData = $model->tiffExists() ? (new Query())->select(new Expression('DISTINCT val::int'))->from($layers)->pluck('val') : [];
$nativeBoundingBox = data_get($model->getFeatureMeta(), 'nativeBoundingBox', []);
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
    .gradient-wrapper { height: 200px; overflow: auto; width: 400px;  }
    .gradient {display: flex; width: 300px; position: relative}
    .gradient-line { margin: 2px 0; cursor: pointer }
    .gradient-line.active { background-color: #ddd }
    .gradient-title { margin-left: 10px; }
    .grad-step {display: inline-block;height: 20px;flex: auto 1 0;}

</style>



<div id="vue-app" class="gallery-form">
    <?php if($model->tiffExists()):?>
        <div>
            <div class="d-flex">
                <div style="margin-right: 10px;">
                    <div id="preview-img"></div>
                </div>
                <?=$this->render('_raster_info', ['model' => $model])?>
            </div>
            <hr>
        </div>
    <?php endif;?>

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
                            'data-toggle'=> 'modal',
                            'data-target'=> '#modal-bands',
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
            <span class="badge badge-light badge-striped badge-striped-left border-left-primary mb-2">CDI = NDVIa * 0.5 +SPI * 0.3333 + LSTa * 0.1667</span>

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
                    'options' => ['placeholder' => 'Chọn ảnh ...', 'multiple' => true, 'id' => 'btnChoseImg'],
                    'pluginOptions' => [
                        'tags' => true,
                    ],
                ])?>
            </div>
            <?php Modal::end(); ?>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <?php if($model->tiffExists()):?>
        <?=$this->render('_reclass_form', ['model' => $model, 'name' => 'm_'.$model->code])?>
    <?php endif;?>


</div>



<?php
$this->registerJsFile('http://seikichi.github.io/tiff.js/tiff.min.js');
$this->registerJsFile('https://cdn.jsdelivr.net/npm/chroma-js@2.1.0/chroma.min.js');
$this->registerJsFile('https://cdn.jsdelivr.net/npm/geostats@1.8.0/lib/geostats.min.js');
?>


<script>

    $(function () {
        let statFns = {
            ei: 'getClassEqInterval',
            qc: 'getClassQuantile',
            sd: 'getClassStdDeviation',
            ap: 'getClassArithmeticProgression',
            gp: 'getClassGeometricProgression',
            nb: 'getClassJenks',
            uv: 'getClassUniqueValues',
        }

        let vm = new Vue({
            el: '#vue-app',
            data: {
                layers: '<?=$layers?>',
                existFile: '<?=$model->tiffExists() ? 1 : 0?>',
                bands_arr: <?=json_encode($items)?>,
                selected: <?=json_encode($model->bands)?>,
                nativeBoundingBox: <?=json_encode($nativeBoundingBox)?>,
                expr: '<?=$model->expr?>',

                gradientSelected: '',
                gradient: [],
                mode: 'nb',
                modes: [
                    {key: 'ei', name: 'Equal Interval '},
                    {key: 'qc', name: 'Equal Count (Quantile)'},
                    {key: 'sd', name: 'Standard Deviation'},
                    {key: 'ap', name: 'Arithmetic Progression'},
                    {key: 'gp', name: 'Geometric Progression'},
                    {key: 'nb', name: 'Natural Breaks (Jenks)'},
                    {key: 'uv', name: 'Uniques Values'},
                ],
                classes_num: 5,
                classes: [
                    {color: 'red', value: 1},
                    {color: 'red', value: 1},
                    {color: 'red', value: 1},
                    {color: 'red', value: 1},
                    {color: 'red', value: 1},
                ],
                statData: <?=json_encode($statData)?>,
                values: [

                ],
                srcMap: ''
            },
            mounted() {
                $('#btnChoseImg').change(this.onChangeBand)

                if(this.existFile){
                    this.gradient = _.map(this.gradient, (v, k) => ({key: k, colors: chroma.scale(k).colors(100)}))

                    this.updateBoxBand()
                    this.previewRaster()
                    this.generateGradient()
                    this.previewMap()
                }
            },

            computed: {
                bands(){
                    return _.map(this.selected).join(',')
                }
            },
            methods: {
                previewMap(){
                    let b = this.nativeBoundingBox,
                        bbox = [b.minx, b.miny, b.maxx, b.maxy].join(','),
                        width = _.ceil($(this.$el).width())

                    width = width ? width-20 : 500
                    this.srcMap = `/maps/preview?layers=drought:m_cdi_2016_03`

                },
                generateGradient(){
                    this.gradient = _.map(chroma.brewer, (v, k) => ({key: k, colors: chroma.scale(k).colors(100)}))
                },
                previewRaster(){
                    let geoTIFFFile = `<?=$img_url?>`

                    if (geoTIFFFile) {
                        let xhr = new XMLHttpRequest();
                        xhr.responseType = 'arraybuffer';
                        xhr.open('GET', geoTIFFFile);
                        xhr.onload = function (e) {
                            let tiff = new Tiff({buffer: xhr.response});
                            let canvas = tiff.toCanvas();
                            $('#preview-img').append(canvas)
                        };
                        xhr.send();
                    }
                },
                activeGradient(e){
                    $('.gradient-line').removeClass('active')
                    let current = $(e.currentTarget)
                    current.addClass('active')
                    this.gradientSelected = current.data('key')
                },
                generateColors(){
                    let classes = this.classes_num,
                        stats = new geostats(this.statData),
                        vStat = stats[statFns[this.mode]](classes)

                    this.values = vStat.map((v, k) => {
                        if(!_.isUndefined(vStat[k+1])) {
                            return {
                                v1: v,
                                v2: vStat[k+1]
                            }
                        }

                        return  null
                    }).filter(v => v)

                    let scale = this.gradientSelected ? this.gradientSelected : 'OrRd',
                        palette = chroma.scale(scale).colors(classes)

                    this.values = this.values.map((v, k) => {
                        return {
                            ...v,
                            color: palette[k]
                        }
                    })
                },
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
                    if(!this.selected) return null

                    $(this.$el).find('#box-band').html(this.selected.map(v => `<option value="${v}">${this.bands_arr[v]}</option>`).join(''))

                    $('#box-band option').dblclick(function() {
                        let id = $(this).val()
                        self.expr += `[${self.bands_arr[id]}]`
                    });
                }
            }
        })
    })

</script>

