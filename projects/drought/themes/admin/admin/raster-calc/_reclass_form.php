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
        <?= Html::hiddenInput('redirectUrl', $redirectUrl) ?>
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

                <v-field class="mt-4" as="select" label="Mode" v-model="symForm.mode" :items="methods"></v-field>
                <v-field type="number" label="Classes" v-model="symForm.nbClass"></v-field>
                <v-field label="Legend Format" v-model="symForm.legendFormat"></v-field>
                <v-field class="m1" as="checkbox" v-model="symForm.invertColorRamp" :items="[{value: 1, text: 'Invert Color Ramp'}]"></v-field>


                <div class="d-flex mt-2" style="justify-content: space-between;">
                    <b-button type="submit" variant="primary">Save</b-button>
                    <b-button variant="success" @click="generateColors">Generate colors</b-button>
                </div>
            </div>


            <div style="padding-left: 30px; flex-grow: 1">
                <table class="table table-symbology">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Symbol</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Legend</th>
                    </tr>
                    </thead>
                    <tbody class="mt-2">
                        <tr v-for="(v, k) in symForm.symbols">
                            <td>{{k+1}}</td>
                            <td><v-color-picker v-model="v.color" :name="`classes[${k}][color]`" class="mr-2 mt-1"></v-color-picker></td>
                            <td><v-field v-model="v.start" :name="'classes['+k+'][start]'"></v-field></td>
                            <td><v-field v-model="v.end" :name="'classes['+k+'][end]'"></v-field></td>
                            <td><v-field v-model="v.legend" :name="'classes['+k+'][legend]'"></v-field></td>
                        </tr>
                    </tbody>
                </table>
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

