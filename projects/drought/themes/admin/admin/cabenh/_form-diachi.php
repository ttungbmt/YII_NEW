<?php

use kartik\widgets\DepDrop;
use yii\helpers\Html;

$noiphCongdong = 2;
?>

<div id="vuediachi">
    <h4>
        <span class="badge badge-light badge-striped badge-striped-left border-left-primary">
            Địa chỉ
        </span>
        <span style="color: #c4c4c4; font-size: 12px;">(Địa chỉ cũ: <?= $model->diachi ?>)</span>
    </h4>
    <?php foreach ($modelsDiachi as $index => $modelDiachi) : ?>
        <div class="card-footer" v-if="isCongdong">

            <h5> <span class="badge badge-light badge-striped badge-striped-left border-left-primary">Địa chỉ <?= $index ?> </span></h5>
            <?php
            if (!$modelDiachi->isNewRecord) {
                echo Html::activeHiddenInput($modelDiachi, "[{$index}]id");
            }
            ?>
            <?= Html::activeRadio($modelDiachi, "is_noi_cl") ?>
            <div class="row">
                <div class="col-md-3">
                    <?= $form->field($modelDiachi, "[{$index}]sonha")->textInput() ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($modelDiachi, "[{$index}]tenduong")->textInput() ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($modelDiachi, "[{$index}]khupho")->textInput() ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($modelDiachi, "[{$index}]to_dp")->textInput() ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($modelDiachi, "[{$index}]matinh")->dropDownList($dm_tinh, [
                        'prompt'  => 'Chọn tỉnh...',
                        'id'      => 'drop-tinh',
                    ]) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($modelDiachi, "[{$index}]maquan")->dropDownList(api('/dm/quan'), [
                        'prompt'  => 'Chọn quận huyện...',
                        'id'      => 'drop-quan',
                    ]) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($modelDiachi, "[{$index}]maphuong")->widget(DepDrop::className(), [
                        'options'       => ['prompt' => 'Chọn phường...'],
                        'pluginOptions' => [
                            'depends'      => ['drop-quan'],
                            'url'          => url(['/api/dm/phuong']),
                            'initialize'   => $modelDiachi->maquan == true,
                            'placeholder'  => 'Chọn phường...',
                            'ajaxSettings' => ['data' => ['value' => $modelDiachi->maphuong]],
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

</div>

<script defer>
    const vueDiachi = new Vue({
        el: '#vuediachi',
        data() {
            return {
                noiphCongdong: <?= $noiphCongdong ?>,
                isCongdong: false
            }
        },
        mounted() {
            this.isCongdong = $('#drop-noiph').val() == this.noiphCongdong;
            $('#drop-noiph').change(() => {
                this.isCongdong = $('#drop-noiph').val() == this.noiphCongdong;
            })
        },
        methods: {

        },
        computed: {
            diachiCount() {
                return this.isCongdong ? 3 : 1;
            }
        },
    })
</script>