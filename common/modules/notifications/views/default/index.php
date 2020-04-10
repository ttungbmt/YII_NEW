<?php
use kartik\widgets\ActiveForm;
use yii\widgets\LinkPager;
use kartik\widgets\DatePicker;
use yii\widgets\Pjax;

$this->title = 'Thông báo';
$model = $searchModel;
$models = $dataProvider->getModels();
$pagination = $dataProvider->getPagination();
$id = 'pjax-container';
?>

<div id="notification-panel">
    <div class="row">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <div class="card-title text-uppercase font-size-sm font-weight-semibold">
                        <i class="icon-bubbles6 mr-2"></i>
                        Thông báo của bạn
                    </div>
                    <div class="header-elements">
                        <div class="list-icons">
                            <a class="list-icons-item" data-action="collapse"></a>
                            <a class="list-icons-item" data-action="reload"></a>
                            <a class="list-icons-item" data-action="remove"></a>
                        </div>
                    </div>
                </div>
                <?php Pjax::begin([
                    'id'           => $id,
                    'scrollTo'     => true,
                    'formSelector' => '#searchContainer form',
//                    'linkSelector' => "#searchContainer a, #{$id} a, #{$id} select",
                ]) ?>

                <ul class="media-list media-list-bordered">
                    <?php foreach ($models as $m):?>
                    <li class="media">
                        <div class="mr-3">
                            <div class="btn bg-success-400 rounded-round btn-icon legitRipple"><i class="icon-aid-kit"></i></div>
                        </div>

                        <div class="media-body">
                            <h6 class="media-title"><?=$m->subject?></h6>
                            <div class="mt-1" title="<?=$m->createdAtText?>"><i class="fa fa-calendar mr-2"></i> <span class="text-muted"><?=$m->createdAtDiffText?></span></div>
                        </div>
                        <div class="ml-3">
                            <?php if(!is_null($m->read_at)):?>
                                <span class="badge badge-primary mr-2" title="<?=$m->readAtDiffText?>">Đã xem</span>
                            <?php endif;?>
                            <div class="list-icons">
                                <div class="list-icons-item dropdown">
                                    <a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-more2"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="#" class="dropdown-item"><i class="fa fa-inbox"></i> Ẩn thông báo này</a>
                                        <?php if(!$m->read_at):?><button id="btnRead" class="dropdown-item act-read" data-href="<?=url(['/notifications/default/read', 'id' => $m->id])?>"><i class="fa fa-check"></i> Đánh dấu là đã đọc</button><?php endif;?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <?php endforeach;?>
                </ul>
                <div class="card-footer d-flex justify-content-between align-items-center">
                    <?= LinkPager::widget([
                        'pagination' => $pagination,
                    ]) ?>
                </div>
                <?php Pjax::end() ?>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-transparent header-elements-inline">
                    <span class="text-uppercase font-size-sm font-weight-semibold">Bộ lọc</span>
                    <div class="header-elements">
                        <div class="list-icons">
                            <a class="list-icons-item" data-action="collapse"></a>
                        </div>
                    </div>
                </div>
                <div id="searchContainer">
                    <?php $form = ActiveForm::begin([
                        'method'  => 'GET',
                    ]) ?>
                    <div class="card-body">
                        <?= $form->field($model, 'date_from')->widget(DatePicker::className()) ?>
                        <?= $form->field($model, 'date_to')->widget(DatePicker::className()) ?>
                        <?= $form->field($model, 'is_seen')->checkboxList([1 => 'Chưa xem', 2 => 'Đã xem']) ?>
                        <button type="submit" class="btn bg-blue btn-block legitRipple">
                            <i class="icon-search4 font-size-base mr-2"></i>
                            Tìm kiếm
                        </button>
                    </div>
                    <?php ActiveForm::end() ?>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    $(function () {
        $('#btnRead').click(function(){
            let href = $(this).data('href')
            $.post(href).done(function(resp){
                $('#notification-panel .media .ml-3').prepend('<span class="badge badge-primary mr-2" title="'+resp.datetime+'">Đã xem</span>')
            })
            $(this).remove()
        })
    })
</script>

