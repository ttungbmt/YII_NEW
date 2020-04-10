<?php
$dm_quan = api('/dm/quan');

use kartik\widgets\DepDrop;

?>
<script type="text/x-template" id="diachi-tpl">
    <div>
        <h4>
            <span class="badge badge-light badge-striped badge-striped-left border-left-warning">Nơi phát hiện</span>
            <span style="color: #c4c4c4; font-size: 12px;">(Địa chỉ cũ: <?= $model->diachi ?>)</span>
        </h4>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'ngay_ph')->widget(\kartik\widgets\DatePicker::className(), [
                    'options' => ['placeholder' => 'DD/MM/YYYY'],
                ]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'noi_ph')->dropDownList($dm_noi_ph, ['prompt' => 'Chọn...', 'id' => 'drop-noiph', 'v-model' => 'model.noi_ph']) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'tennoi_ph')->textInput() ?>
            </div>
        </div>

        <div class="card-footer pt-0">
            <div v-for="(m, index) in items">
                <?= $form->field($dc, "[]type")->hiddenInput([':name' => '`Diachi[${index}][type]`', 'value' => 2])->label(false) ?>
                <?= $form->field($dc, "[]id")->hiddenInput([':name' => '`Diachi[${index}][id]`', 'v-model' => 'm.id'])->label(false) ?>
                <div class="d-flex box-uutien">
                    <?= $form->field($model, "uutien")->radio([':value' => 'index'])->label('<h5 class="mr-2"> <span class="badge badge-flat border-success text-success-600">Địa chỉ {{index+1}}</span></h5>') ?>
                    <div class="mt-1 ml-2 cursor-pointer" @click="removeModel(index)" v-if="items.length > 1"><i
                            class="text-danger icon-bin"/></div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($dc, "[]geom[0]")->textInput([':name' => '`Diachi[${index}][geom][1]`', 'v-model' => 'm.geom[1]'])->label('Lat') ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($dc, "[]geom[1]")->textInput([':name' => '`Diachi[${index}][geom][0]`', 'v-model' => 'm.geom[0]'])->label('Lng') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <?= $form->field($dc, "[]sonha")->textInput([':name' => '`Diachi[${index}][sonha]`', 'v-model' => 'm.sonha']) ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($dc, "[]tenduong")->textInput([':name' => '`Diachi[${index}][tenduong]`', 'v-model' => 'm.tenduong']) ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($dc, "[]khupho")->textInput([':name' => '`Diachi[${index}][khupho]`', 'v-model' => 'm.khupho']) ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($dc, "[]to_dp")->textInput([':name' => '`Diachi[${index}][to_dp]`', 'v-model' => 'm.to_dp']) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($dc, "[]matinh")->dropDownList($dm_tinh, ['prompt' => 'Chọn tỉnh', ':name' => '`Diachi[${index}][matinh]`', 'v-model' => 'm.matinh']) ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($dc, "[]maquan")->dropDownList($dm_quan, ['class' => 'form-control drop-dc-quan', ':id' => '`drop-quan-${index}`', ':name' => '`Diachi[${index}][maquan]`', 'prompt' => 'Chọn quận huyện...', 'v-model' => 'm.maquan']) ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($dc, "[]maphuong")->dropDownList([], ['class' => 'form-control drop-dc-phuong', ':name' => '`Diachi[${index}][maphuong]`', 'prompt' => 'Chọn phường xã...', 'v-model' => 'm.maphuong', ':data-depends' => '`["drop-quan-${index}"]`']) ?>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-primary" style="width: 100%; padding: 4px;" @click="addModel"
                    v-if="model.noi_ph == '3'">+
            </button>
        </div>

        <h4 class="mt-2">
            <span class="badge badge-light badge-striped badge-striped-left border-left-warning">Nơi đang cách ly</span>
        </h4>
        <div class="card-footer pt-0">
            <?= $form->field($noi_dcl, "[]type")->hiddenInput([':name' => '`noi_dcl[type]`', 'value' => 1])->label(false) ?>
            <?= $form->field($noi_dcl, "[]id")->hiddenInput([':name' => '`noi_dcl[id]`'])->label(false) ?>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($noi_dcl, "geom[1]")->textInput([':name' => '`noi_dcl[geom][1]`'])->label('Lat') ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($noi_dcl, "geom[0]")->textInput([':name' => '`noi_dcl[geom][0]`'])->label('Lng') ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <?= $form->field($noi_dcl, "[]sonha")->textInput([':name' => '`noi_dcl[sonha]`']) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($noi_dcl, "[]tenduong")->textInput([':name' => '`noi_dcl[tenduong]`']) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($noi_dcl, "[]khupho")->textInput([':name' => '`noi_dcl[khupho]`']) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($noi_dcl, "[]to_dp")->textInput([':name' => '`noi_dcl[to_dp]`']) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($noi_dcl, "[]matinh")->dropDownList($dm_tinh, ['prompt' => 'Chọn tỉnh', ':name' => '`noi_dcl[matinh]`']) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($noi_dcl, "[]maquan")->dropDownList($dm_quan, [':name' => '`noi_dcl[maquan]`', 'prompt' => 'Chọn quận huyện...', 'id' => 'ndcl_quan']) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($noi_dcl, '[]maphuong')->widget(DepDrop::className(), [
                        'options'       => [
                            'id' => 'ndcl_phuong',
                            'prompt' => 'Chọn phường...',
                            ':name'  => '`noi_dcl[maphuong]`',
                        ],
                        'pluginOptions' => [
                            'depends'      => ['ndcl_quan'],
                            'url'          => url(['/api/dm/phuong']),
                            'initialize'   => $noi_dcl->maquan == true,
                            'placeholder'  => 'Chọn phường...',
                            'ajaxSettings' => ['data' => ['value' => $noi_dcl->maphuong]],
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</script>