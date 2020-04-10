<?php

use common\assets\AppPluginAsset;
use ttungbmt\noty\Noty;

AppPluginAsset::register($this);
\common\assets\BeginAsset::register($this);
\common\assets\EndAsset::register($this);
$this->render('@app/views/partials/assets');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= app('language') ?>">
<head>
    <?= $this->render('../partials/meta') ?>
    <title> <?= $this->title ?> | <?= params('app_name') ?></title>
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?libraries=places&language=vi&region=VN&key=<?= params('googleKey') ?>"></script>
    <?php $this->head() ?>
    <?= data_get($this, 'blocks.styles') ?>
    <style>
        .sidebar-user-material .sidebar-user-material-body {
            background: url(<?=url('/themes/admin/main/images/backgrounds/user_bg.jpg')?>) center center no-repeat;
            background-size: cover;
        }
    </style>

</head>
<body>
<?php $this->beginBody() ?>
<!-- Main navbar -->
<?= $this->render('@app/views/partials/navbar') ?>
<!-- /main navbar -->

<!-- Page content -->
<div class="page-content">

    <!-- Main sidebar -->
    <?= $this->render('@app/views/partials/sidebar') ?>
    <!-- /main sidebar -->

    <!-- Main content -->
    <div class="content-wrapper">
        <!-- Page header -->
        <?= $this->render('@app/views/partials/header') ?>
        <!-- /page header -->

        <!-- Content area -->
        <div class="content">
            <?= Noty::widget() ?>
            <?= $content ?>
        </div>
        <!-- /content area -->

        <!-- Footer -->
        <?= $this->render('@app/views/partials/footer') ?>
        <!-- /footer -->

    </div>
    <!-- /main content -->

</div>
<!-- /page content -->
<?= data_get($this, 'blocks.beforeScripts') ?>
<?php $this->endBody() ?>
<?= data_get($this, 'blocks.scripts') ?>

</body>
</html>
<?php $this->endPage() ?>
