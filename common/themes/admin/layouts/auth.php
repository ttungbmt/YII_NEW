<?php
\common\assets\AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?=app('language')?>">
<head>
    <?=$this->render('../partials/meta')?>
    <title> <?=$this->title?> | <?=params('app_name')?></title>
    <?php $this->head() ?>

    <style>
        .login-cover {
            background: url(<?= url(params('assets.login_background')) ?>) no-repeat;
            background-size: cover;
        }
        .red {
            color: #F83333;
            text-shadow: 2px 2px 2px rgba(150, 150, 150, 0.35);
            text-transform: uppercase;
            font-size: 17px;
        }
    </style>
</head>
<body class="login-container login-cover">
<?php $this->beginBody() ?>
<!-- Page container -->
<div class="page-container no-padding">

    <!-- Page content -->
    <div class="page-content">

        <!-- Main content -->
        <div class="content-wrapper">

            <!-- Content area -->
            <div class="content d-flex justify-content-center align-items-center" style="padding-top: 50px">
                <?=$content?>
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
