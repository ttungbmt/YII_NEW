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
<html lang="<?=app('language')?>">
<head>
    <?=$this->render('../partials/meta')?>
    <title> <?=$this->title?> | <?=params('app_name')?></title>
    <script async defer src="https://maps.googleapis.com/maps/api/js?libraries=places&language=vi&region=VN&key=<?=params('googleKey')?>"></script>
    <?php $this->head() ?>
    <?=data_get($this, 'blocks.styles')?>
</head>
<body data-spy="scroll" data-target=".sidebar-detached" class="has-detached-right">
<?php $this->beginBody() ?>
<div id="app-wrapper">
    <!-- Main navbar -->
    <?=$this->render('@app/views/partials/navbar')?>
    <!-- /main navbar -->

    <!-- Page container -->
    <div class="page-container">

        <!-- Page content -->
        <div class="page-content">
            <?=$this->render('@app/views/partials/sidebar')?>
            <!-- Main content -->
            <div class="content-wrapper">
                <?=$this->render('@app/views/partials/header')?>

                <div class="content">
                    <?= Noty::widget() ?>

                    <?=$content?>
                    <?=$this->render('@app/views/partials/footer')?>
                </div>
                <!-- /content area -->

            </div>
            <!-- /main content -->

        </div>
        <!-- /page content -->
    </div>

</div>
<?php $this->endBody() ?>
<?=data_get($this, 'blocks.scripts')?>
</body>
</html>
<?php $this->endPage() ?>
