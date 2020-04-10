<?php
use kartik\form\ActiveForm;

$this->title = 'Quên mật khẩu';
?>
<?php $form = ActiveForm::begin(); ?>
<div class="card card-body login-form">
    <div class="text-center">
        <div class="thumb thumb-rounded">
            <i class="icon-unlocked2 icon-3x text-muted"></i>
        </div>

        <h5 class="content-group text-semibold">QUÊN MẬT KHẨU?</h5>
    </div>

    <div class="content-group">
        <div>
            <h6><small>Nhập email đăng ký tài khoản và chọn "Khôi phục". Chúng tôi sẽ gửi mật khẩu mới vào email của bạn</small></h6>
        </div>
    </div>

    <div class="form-group has-feedback">
        <input type="email" class="form-control" placeholder="Email">
        <div class="form-control-feedback">
            <i class="icon-mail5 text-muted"></i>
        </div>
    </div>

    <button type="submit" class="btn bg-blue-400 btn-block">Khôi phục <i class="icon-arrow-right14 position-right"></i></button>
</div>
<!-- /advanced login -->