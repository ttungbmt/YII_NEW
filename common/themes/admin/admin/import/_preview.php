<?php

use kartik\alert\Alert;
use kartik\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\TabularForm;
use yii\widgets\LinkPager;

?>
<style>
    /*#previewForm table thead { background-color: #eee; }*/

    /*.kv-panel-after { padding: 10px }*/

    /*.fade:not(.show) {*/
    /*opacity: 1 !important;*/
    /*}*/
</style>

<?= $this->render('_errors', ['models' => $dataProvider->getModels()]) ?>

<?php if (isset($errors)): ?>
    <?= $this->render('_errors', ['models' => $errors]) ?>
<?php endif; ?>

<?php if (isset($message)): ?>
    <?= Alert::widget([
        'options' => ['class' => 'no-border mb-1'],
        'body'    => $message,
    ]) ?>
    <script>
        if (window.pages) {
            window.pages.push(<?=$page?>)
        } else {
            window.pages = [<?=$page?>]
        }
    </script>
<?php endif; ?>


<?php if (request('type') !== 'save-all'): ?>
    <?php if (!isset($message)): ?>
        <?php $form = ActiveForm::begin([
            'id' => 'previewForm',
        ]) ?>

        <?= TabularForm::widget([
            'form'              => $form,
            'dataProvider'      => $dataProvider,
            'attributeDefaults' => [
                'type' => TabularForm::INPUT_TEXT,
            ],
            'attributes'        => array_first($models)->formAttrs(),
            'checkboxColumn'    => false,
            'actionColumn'      => false,
            'gridSettings'      => [
                'resizableColumns' => true,
                'containerOptions' => ['style' => 'height:600px'],
                'panel'            => [
                    'heading' => 'Danh sách Ca bệnh',
                    'before'  => false,
                    'after'   => false,
                    'footer'  => false,
                ]
            ]
        ]) ?>
        <?php ActiveForm::end() ?>
    <?php endif; ?>

    <div class="mb-4">
        <div class="row">
            <div class="col-md-10">
                <div class="d-flex justify-content-between align-items-center">
                    <?php if (!isset($message)): ?>
                        <?= LinkPager::widget([
                            'pagination' => $dataProvider->getPagination(),
                        ]) ?>
                    <?php endif; ?>
                </div>

            </div>
            <div class="text-right col-md-2">
                <?php if (!isset($hideBtnImport) && !isset($message)): ?>
                    <?= Html::a('<span class="ladda-label"><i class="icon-cloud text-size-base position-left"></i> Nhập dữ liệu</span>', '#', ['class' => 'btn bg-blue btn-ladda btn-ladda-spinner', 'id' => 'btnSubmit', 'data-style' => 'zoom-in']) ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        $(function () {
            $('.pagination li a').click(function (e) {
                e.preventDefault()

                $(this).html('<i class="fa fa-spinner fa-spin"></i>')
                let data = new FormData($('#uploadForm')[0])
                data.append('pages', _.uniq(window.pages))

                let page = $(e.target).data('page')
                data.append('page', page)
                let url = '/admin/cabenh-import?type=preview'
                $.ajax({
                    url,
                    type: 'POST',
                    data,
                    processData: false,
                    contentType: false,
                }).done(res => {
                    $('#importResp').html(res.html)
                    window.scrollTo(0, 0);
                })
            })


            $('#btnSubmit').click(function (e) {
                e.preventDefault()
                let l = Ladda.create($('#btnSubmit')[0])
                let url = '/admin/cabenh-import?type=save'
                let formUpload = new FormData($('#uploadForm')[0])
                formUpload.append('page', <?=$page?>)
                formUpload.append('pages', [])
                let formPreview = new FormData($("#previewForm")[0])
                for (let pair of formPreview.entries()) {
                    formUpload.append(pair[0], pair[1]);
                }


                l.start()
                $.ajax({
                    url,
                    type: 'POST',
                    data: formUpload,
                    processData: false,
                    contentType: false,
                }).done(res => {
                    if (res.status == 'OK') {

                    } else {
                        $('#importResp').html(res.html)
                    }
                    l.stop()
                })
            })

            $('.kv-grid-table').floatThead({
                scrollContainer: function ($table) {
                    $table.find('thead').css('background-color', 'white')
                    return $table.closest('.kv-grid-container');
                }
            })
        })
    </script>
<?php endif; ?>

