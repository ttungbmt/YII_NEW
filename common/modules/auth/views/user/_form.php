<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $model->titlePage('Người dùng');
$roles = collect(auth()->getRoles())->map(function ($item){return $item->description;})->prepend('Chọn phân quyền', '')->all();
$userRoles = array_keys(auth()->getRolesByUser($model->id));
?>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="tabbable">
                <ul class="nav nav-tabs nav-tabs-highlight nav-justified">
                    <li class="nav-item"><a href="#tab-userinfo" data-toggle="tab" class="nav-link active"><i class="icon-profile position-left"></i> Thông tin chung</a></li>
                    <?php if(!$model->isNewRecord):?>
                    <li class="nav-item"><a href="#tab-resetpass" data-toggle="tab" class="nav-link"><i class="icon-reset position-left"></i> Đổi mật khẩu</a></li>
                    <?php endif;?>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="tab-userinfo">
                        <div class="card-body">

                            <div class="user-form">
                                <?php $form = ActiveForm::begin(['action' =>
                                    ($model->isNewRecord ? ['/auth/user/create'] : ['/auth/user/update', 'id' => $model->getId()])
                                ]); ?>

                                <fieldset>
                                    <legend class="font-weight-semibold text-uppercase font-size-sm text-primary">
                                        <i class="icon-vcard position-left"></i>
                                        Tài khoản
                                        <a class="float-right text-default" data-toggle="collapse" data-target="#field-account">
                                            <i class="icon-circle-down2"></i>
                                        </a>
                                    </legend>

                                    <div class="collapse show" id="field-account">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
                                            </div>
                                            <div class="col-md-6">
                                                <?= $form->field($model, 'email')->textInput(['type' => 'email']) ?>
                                            </div>
                                        </div>

                                        <?php if($model->isNewRecord): ?>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <?= $form->field($model, 'password_hash')->passwordInput(['maxlength' => true]) ?>
                                                </div>
                                                <div class="col-md-6">
                                                    <?= $form->field($model, 'repeat_password_hash')->passwordInput(['maxlength' => true]) ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <?php if(user()->can('admin')):?>
                                            <?= $form->field($model, 'status')->radioList([1 => 'Kích hoạt', 0 => 'Chưa kích hoạt', 3 => 'Chặn tài khoản']) ?>
                                        <?php endif;?>

                                    </div>
                                </fieldset>

                                <?php if(user()->can('admin')):?>
                                    <fieldset>
                                        <legend class="font-weight-semibold text-uppercase font-size-sm text-primary">
                                            <i class="icon-lock position-left"></i>
                                            Phân quyền người dùng
                                            <a class="float-right text-default" data-toggle="collapse" data-target="#field-roles">
                                                <i class="icon-circle-down2"></i>
                                            </a>
                                        </legend>

                                        <div class="collapse show" id="field-roles">
                                            <?= $form->field($model, 'roles')->dropDownList($roles, ['multiple' => true, 'class' => 'form-control']) ?>
                                        </div>
                                    </fieldset>
                                <?php endif;?>

                                <fieldset>
                                    <legend class="font-weight-semibold text-uppercase font-size-sm text-primary">
                                        <i class="icon-person position-left"></i>
                                        Thông tin cá nhân
                                        <a class="float-right text-default" data-toggle="collapse" data-target="#field-personal">
                                            <i class="icon-circle-down2"></i>
                                        </a>
                                    </legend>

                                    <div class="collapse show" id="field-personal">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?= $form->field($model, 'fullname') ?>
                                            </div>
                                            <div class="col-md-6">
                                                <?= $form->field($model, 'chucdanh') ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?= $form->field($model, 'phone') ?>
                                            </div>
                                            <div class="col-md-6">

                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <?= $form->field($model, 'address') ?>
                                            </div>
                                            <div class="col-md-6">
                                                <?= $form->field($model, 'gender')->radioList([1 => 'Nam', 0 => 'Nữ']) ?>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>

                                <div class="form-group pull-right">
                                    <?= Html::submitButton($model->isNewRecord ? Yii::t('app', trans('app', 'Create')) : Yii::t('app', trans('app', 'Update')), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                                </div>

                                <?php ActiveForm::end(); ?>

                            </div>
                        </div>

                    </div>

                    <?php if(!$model->isNewRecord):?>
                    <div class="tab-pane fade" id="tab-resetpass">
                        <div class="card-body">
                            <?php $form = ActiveForm::begin(['action' => url(['/auth/user/reset-password', 'id' => $model->getId()])]); ?>

                            <?= $form->field($model, 'new_password')->passwordInput(['maxlength' => true, 'id' => 'reset-pass'])->label('Mật khẩu mới') ?>
                            <?= $form->field($model, 'new_password_repeat')->passwordInput(['id' => 'reset-pass-repeat'])->label('Nhập lại mật khẩu mới') ?>

                            <div class="form-group pull-right">
                                <?= Html::submitButton('Đổi mật khẩu', ['class' => 'btn btn-primary']) ?>
                            </div>

                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body bg-blue text-center card-img-top" style="background-image: url(http://demo.interface.club/limitless/assets/images/bg.png); background-size: contain;">
                <div class="card-img-actions d-inline-block mb-3">
                    <img class="img-fluid rounded-circle" src="https://encrypted-tbn2.gstatic.com/images?q=tbn:ANd9GcSG6fwgVi4FtsNNn1lXnUwBmes8PCxp3-VlukOVNjq-byO7BJxXkg" width="170" height="170" alt="">
                </div>
                <h5 class="font-weight-semibold mb-0"><?=$model->fullname?></h5>
                <span class="d-block opacity-75"><?=$model->chucdanh?></span>

                <ul class="list-inline list-inline-condensed mt-3 mb-0">
                    <li class="list-inline-item">
                        <a href="#" class="btn btn-outline bg-white btn-icon text-white border-white border-2 rounded-round">
                            <i class="icon-phone"></i>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a href="#" class="btn btn-outline bg-white btn-icon text-white border-white border-2 rounded-round">
                            <i class="icon-bubbles4"></i>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a href="#" class="btn btn-outline bg-white btn-icon text-white border-white border-2 rounded-round">
                            <i class="icon-envelop4"></i>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body border-top-0">
                <div class="d-sm-flex flex-sm-wrap mb-3">
                    <div class="font-weight-semibold">Họ tên:</div>
                    <div class="ml-sm-auto mt-2 mt-sm-0"><?=$model->fullname?></div>
                </div>
                <div class="d-sm-flex flex-sm-wrap mb-3">
                    <div class="font-weight-semibold">Email:</div>
                    <div class="ml-sm-auto mt-2 mt-sm-0"><a href="mailto:<?=$model->email?>"><?=$model->email?></a></div>
                </div>
                <div class="d-sm-flex flex-sm-wrap">
                    <div class="font-weight-semibold">Điện thoại:</div>
                    <div class="ml-sm-auto mt-2 mt-sm-0"><a href="tel:<?=$model->phone?>"><?=$model->phone?></a></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    
</script>

