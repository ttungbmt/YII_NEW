<?php
use kartik\form\ActiveForm;

$this->title = 'Đăng ký tài khoản';
$prefix = function ($icon){
    return [
        'feedbackIcon' => ['default' => $icon.' text-muted', 'prefix' => ''],
        'options' => ['class' => 'has-feedback-left']
    ];
}
?>
<?php $form = ActiveForm::begin(['enableClientValidation' => false]); ?>
<div class="card card-body login-form">
    <div class="text-center">
        <div class="mb-20" >
            <img src="<?= url(params('assets.logo')) ?>" style="max-width: 230px">
        </div>
        <b class="red"><?=params('app_name')?> </b>
        <h5 class="content-group text-semibold">ĐĂNG KÝ TÀI KHOẢN MỚI</h5>
    </div>

    <div class="form-group">
        <?=$form->field($model, 'username', $prefix('icon-user'))->textInput(['placeholder' => 'Tên đăng nhập'])->label(false)?>
    </div>


    <div class="form-group">
        <?=$form->field($model, 'email', $prefix('icon-mail5'))->textInput(['placeholder' => 'Email'])->label(false)?>
    </div>
    <div class="form-group">
        <?=$form->field($model, 'password_hash', $prefix('icon-lock'))->textInput(['placeholder' => 'Mật khẩu'])->label(false)?>
    </div>
    <div class="form-group">
        <?=$form->field($model, 'password_hash', $prefix('icon-lock'))->textInput(['placeholder' => 'Nhập lại mật khẩu'])->label(false)?>
    </div>

<!--    <div class="form-group has-feedback has-feedback-left">-->
<!--        <input type="text" class="form-control" placeholder="Email">-->
<!--        <div class="form-control-feedback">-->
<!--            <i class="icon-mention text-muted"></i>-->
<!--        </div>-->
<!--    </div>-->

<!--    <div class="form-group has-feedback has-feedback-left">-->
<!--        <input type="text" class="form-control" placeholder="Nhập lại Email">-->
<!--        <div class="form-control-feedback">-->
<!--            <i class="icon-mention text-muted"></i>-->
<!--        </div>-->
<!--    </div>-->

    <button type="submit" class="btn bg-blue-400 btn-block btn-lg">Đăng ký <i class="icon-circle-right2 position-right"></i></button>

    <div class="content-group">
        <div class="text-center">
            <h5><small class="display-block">Đã có tài khoản? <a href="/login">Đăng nhập</a></small></h5>
        </div>
    </div>

    <div class="content-divider text-muted form-group mb-10"><span>Copyright</span></div>
    <span class="help-block text-center no-margin">
            <?=date('Y')?> @ <?=params('copyright')?>
        </span>
</div>
<?php ActiveForm::end(); ?>
<!-- /advanced login -->