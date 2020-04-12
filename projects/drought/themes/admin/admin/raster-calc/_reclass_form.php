<?php

use drought\support\Jenks;
use kartik\widgets\ActiveForm;
use ttungbmt\db\Query;
use yii\db\Expression;
use yii\helpers\Html;

?>


<div class="card">
    <div class="card-body">
        <?php $form = ActiveForm::begin([
            'action' => '/admin/raster-calc/save-classes?id='.$model->id,
            'type' => ActiveForm::TYPE_HORIZONTAL,
            'enableClientValidation' => false,
            'options' => ['enctype' => 'multipart/form-data']
        ]); ?>
        <?= Html::hiddenInput('name', $name) ?>
        <div class="d-flex" style="width: 100%">
            <div>
                <div class="gradient-wrapper">
                    <div v-for="g in colorRamp" class="d-flex gradient-line" @click="activeGradient" :data-key="g.key">
                        <div class="gradient">
                            <span class="grad-step" :style="{backgroundColor: v}" v-for="v in g.colors"></span>
                        </div>
                        <div class="gradient-title">{{_.upperFirst(g.key)}}</div>
                    </div>
                </div>

                <div class="form-group mt-4">
                    <label for="symb-mode" class="control-label">Mode</label>
                    <select name="mode" id="symb-mode" class="form-control" v-model="symbology.mode">
                        <option :value="m.key" v-for="m in symbology.methods">{{m.name}}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="symb-classes" class="control-label">Classes</label>
                    <?= Html::input('text', 'classes_num', null, ['class' => 'form-control', 'v-model' => 'symbology.number']) ?>
                </div>

                <div class="d-flex mt-2" style="justify-content: space-between;">
                    <button type="submit" class="btn btn-sm btn-primary">Save</button>
                    <button type="button" class="btn btn-sm btn-success" @click="generateColors">Generate colors</button>
                </div>
            </div>


            <div style="padding-left: 30px; flex-grow: 1">
                <div v-for="(v, k) in symbols" class="d-flex">
                    <?= Html::input('text', "classes", null, ['class' => 'mt-2 mr-4', 'type' => 'color', 'v-model' => 'v.color', ':name' => "'classes['+k+'][color]'"]) ?>
                    <?= Html::input('text', "classes", null, ['class' => 'form-control', 'v-model' => 'v.start', ':name' => "'classes['+k+'][start]'"]) ?>
                    <?= Html::input('text', "classes", null, ['class' => 'form-control', 'v-model' => 'v.end', ':name' => "'classes['+k+'][end]'"]) ?>
                </div>

            </div>
        </div>


        <?php ActiveForm::end(); ?>
    </div>
</div>
<div class="card card-map" v-if="geoserver.layers">
    <div class="card-header">
        <span class="badge badge-light badge-striped badge-striped-left border-left-primary" style="font-size: 16px">Bản đồ</span>
    </div>
    <iframe height="1000" :src="`/maps/preview?layers=`+geoserver.layers" frameborder="0" allowfullscreen style="padding: 0 10px"></iframe>
</div>

