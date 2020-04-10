<?php

use kartik\form\ActiveForm;
use yii\helpers\Html;

$this->title = 'Đăng nhập hệ thống';
?>
<?php $form = ActiveForm::begin(['enableClientValidation' => false]); ?>
<div class="card card-body login-form">
    <div class="text-center mb-2">
        <div class="mb-4">
            <img src="<?= url(params('assets.logo')) ?>" style="max-height: 150px; max-width: 100%">
        </div>
        <h5 class="content-group"><b class="red"><?= params('app_name_login', params('app_name')) ?></b>
        </h5>
        <span class="d-block text-muted">Thông tin tài khoản</span>
    </div>

    <?= $form->field($model, 'username', [
        'options'           => ['class' => 'form-group-feedback form-group-feedback-left mb-2'],
        'contentAfterInput' => Html::tag('div', '<i class="icon-user text-muted"></i>', ['class' => 'form-control-feedback'])
    ])->textInput(['placeholder' => 'Tên đăng nhập', 'autofocus' => true])->label(false) ?>

    <div class="form-group has-feedback has-feedback-left">
        <?= $form->field($model, 'password', [
            'options'           => ['class' => 'form-group-feedback form-group-feedback-left'],
            'contentAfterInput' => Html::tag('div', '<i class="icon-lock text-muted"></i>', ['class' => 'form-control-feedback'])
        ])->passwordInput(['placeholder' => 'Mật khẩu'])->label(false) ?>
    </div>

    <div class="form-group login-options">
        <div class="row">
            <div class="col-sm-6">
                <?= $form->field($model, 'rememberMe')->checkbox([
                    'template' => "{input} {label} 	&nbsp; Ghi nhớ",
                    'class'    => 'styled'
                ])->label(false) ?>
            </div>

            <div class="col-sm-6 text-right">
                <a href="<?= url('/forget-password') ?>">Quên mật khẩu?</a>
            </div>
        </div>
    </div>

    <div class="form-group">
        <button type="submit" class="btn bg-blue-400 btn-block">Đăng nhập <i class="icon-arrow-right14 position-right"></i></button>
    </div>

    <?=$this->render('@app/views/partials/_extra_login')?>

    <div class="content-divider text-muted form-group mb-10"><span>Copyright</span></div>
    <span class="help-block text-center no-margin">
            <?= date('Y') ?> @ <?= params('copyright') ?>
        </span>
</div>
<?php ActiveForm::end(); ?>
<!-- /advanced login -->