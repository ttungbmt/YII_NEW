<?php

use kartik\helpers\Html;
use kartik\widgets\ActiveForm;
use pcd\models\HcPhuong;

$this->title = 'Nhập Excel Ca bệnh';
?>
    <div class="card card-white">
        <?php $form = ActiveForm::begin([
            'options' => ['id' => 'uploadForm', 'enctype' => 'multipart/form-data'],
        ]); ?>
        <div class="card-header bg-light header-elements-inline">
            <div class="card-title text-semibold text-uppercase font-weight-semibold">
                <i class="icon-database-add text-size-base position-left"></i>
                Nhập dữ liệu Excel
            </div>
            <div class="heading-elements">
                <?= Html::a('<i class="icon-file-download"></i> Tải file mẫu', $sampleFile, ['class' => 'btn btn-outline bg-primary-400 text-primary-400 border-primary-400']) ?>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'file')->fileInput()->label(false) ?>
                </div>
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <div class="btn-group pull-right">
                        <?= Html::submitButton('<span class="ladda-label"> <i class="icon-cloud-upload"></i> Xem trước</span>', ['id' => 'btnPreview', 'name' => 'btnPreview', 'class' => 'btn btn-success btn-ladda btn-ladda-spinner', 'data-style' => 'zoom-in']) ?>
                        <?= Html::button('<span class="ladda-label"><i class="icon-database-insert"></i> Nhập tất cả</span>', ['id' => 'btnSaveAll', 'class' => 'btn btn-primary btn-ladda btn-ladda-spinner', 'data-style' => 'zoom-in']) ?>
                    </div>
                </div>
            </div>
        </div>
        <?php ActiveForm::end() ?>


    </div>

    <div id="importResp"></div>

   <?=$this->render($instructionView)?>

<?php $this->beginBlock('scripts') ?>
    <script>
        $(function () {
            const postApi = (url, data) => {
                return $.ajax({
                    url,
                    type: 'POST',
                    data,
                    processData: false,
                    contentType: false,
                })
            }

            $('#uploadForm').submit(function (e) {

                document.cookie = "refesh=true";
                e.preventDefault()
                window.pages = []

                let data = new FormData(this)
                let l = Ladda.create($('#btnPreview')[0])
                l.start()
                const api = postApi(`/admin/cabenh-import?type=preview`, data)
                api.done(res => {
                    l.stop()
                    $('#importResp').html(res.html)
                }).fail(() => l.stop())
            })

            $('#btnSaveAll').click(function (e) {
                window.pages = []

                e.preventDefault()
                let l = Ladda.create($(this)[0])
                let data = new FormData($('#uploadForm')[0])
                const api = postApi(`/admin/cabenh-import?type=save-all`, data)
                l.start()
                api.done(res => {
                    l.stop()
                    $('#importResp').html(res.html)
                }).fail(() => l.stop())
            })

        })
    </script>
<?php $this->endBlock() ?>