<?php
use kartik\widgets\ActiveForm;
$this->title = 'Reset password';
?>
<?php $form = ActiveForm::begin(); ?>
    <div class="card card-body login-form">
        <div class="thumb thumb-rounded">
            <img src="assets/images/demo/users/face11.jpg" alt="">
            <div class="caption-overflow">
                                    <span>
                                        <a href="#" class="btn border-white text-white btn-flat btn-icon btn-rounded btn-xs"><i class="icon-collaboration"></i></a>
                                        <a href="#" class="btn border-white text-white btn-flat btn-icon btn-rounded btn-xs ml-5"><i class="icon-question7"></i></a>
                                    </span>
            </div>
        </div>

        <h6 class="content-group text-center text-semibold no-margin-top">Victoria Baker <small class="display-block">Unlock your account</small></h6>

        <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="Your password">
            <div class="form-control-feedback">
                <i class="icon-user-lock text-muted"></i>
            </div>
        </div>

        <div class="form-group login-options">
            <div class="row">
                <div class="col-sm-6">
                    <label class="checkbox-inline">
                        <input type="checkbox" class="styled">
                        Remember
                    </label>
                </div>

                <div class="col-sm-6 text-right">
                    <a href="login_password_recover.html">Forgot password?</a>
                </div>
            </div>
        </div>

        <button type="submit" class="btn bg-pink-400 btn-block">Unlock <i class="icon-arrow-right14 position-right"></i></button>
    </div>
<?php ActiveForm::end(); ?>