<?php
/* @var $exception \yii\web\HttpException|\Exception */
/* @var $handler \yii\web\ErrorHandler */
if ($exception instanceof \yii\web\HttpException) {
    $code = $exception->statusCode;
} else {
    $code = $exception->getCode();
}
$name = $handler->getExceptionName($exception);
if ($name === null) {
    $name = 'Error';
}
if ($code) {
    $name .= " (#$code)";
}

if ($exception instanceof \yii\base\UserException) {
    $message = $exception->getMessage();
} else {
    $message = 'An internal server error occurred.';
}

if (method_exists($this, 'beginPage')) {
    $this->beginPage();
}
?>



<?php
\common\assets\AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?=app('language')?>">
<head>
    <?=$this->render('../partials/meta')?>
    <title> <?= $handler->htmlEncode($name) ?> | <?=params('app_name')?></title>
    <?php $this->head() ?>
</head>
<body class="login-container">
<?php $this->beginBody() ?>
<!-- Page container -->
<div class="page-container">

    <!-- Page content -->
    <div class="page-content">

        <!-- Main content -->
        <div class="content-wrapper">

            <!-- Content area -->
            <div class="content d-flex justify-content-center align-items-center">

                <div class="flex-fill">
                    <div class="text-center mb-3">
                        <h1 class="error-title"><?= $code ?></h1>
                        <h5><?= nl2br($handler->htmlEncode($message)) ?></h5>
                        <h6>Vui lòng liên hệ với quản trị viên để được hỗ trợ</h6>
                    </div>

                    <div class="row">
                        <div class="col-xl-4 offset-xl-4 col-md-8 offset-md-2">
                            <div class="text-center">
                                <a href="<?=url('/')?>" class="btn bg-pink-400"><i class="icon-circle-left2 position-left"></i> Về trang chủ</a>
                            </div>
                            <!-- Footer -->
                            <!-- /footer -->
                        </div>

                    </div>


                </div>

            </div>
            <!-- /content area -->

        </div>
        <!-- /main content -->

    </div>
    <!-- /page content -->

</div>
<!-- /page container -->
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
