<?php
use kartik\widgets\ActiveForm;
?>
<?php $form = ActiveForm::begin(); ?>
    <div class="card card-body login-form">
        <div class="thumb thumb-rounded">
            <i class="icon-unlocked2 icon-3x text-muted"></i>
        </div>

        <div class="text-center">
            <h5 class="content-group text-semibold">MỞ KHÓA TÀI KHOẢN</h5>
        </div>

        <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="Mật khẩu">
            <div class="form-control-feedback">
                <i class="icon-user-lock text-muted"></i>
            </div>
        </div>

        <div class="form-group login-options">
            <div class="row">
                <div class="col-sm-6">
                    <label class="checkbox-inline">
                        <input type="checkbox" class="styled">
                        Ghi nhớ tôi
                    </label>
                </div>

                <div class="col-sm-6 text-right">
                    <a href="login_password_recover.html">Quên mật khẩu?</a>
                </div>
            </div>
        </div>

        <button type="submit" class="btn bg-blue-400 btn-block">Mở khóa tài khoản<i class="icon-arrow-right14 position-right"></i></button>
    </div>
<?php ActiveForm::end(); ?>