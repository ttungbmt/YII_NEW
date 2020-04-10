<?php
use common\assets\ReactMapAsset;
ReactMapAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?=app('language')?>">
<head>
    <?=$this->render('../partials/meta')?>
    <title> <?=$this->title?> | <?=params('app_name')?></title>
    <?php $this->head() ?>
    <?=data_get($this, 'blocks.styles')?>
    <script async defer src="https://maps.googleapis.com/maps/api/js?libraries=places&language=vi&region=VN&key=<?=params('googleKey')?>"></script>
</head>
<body>
<?php $this->beginBody() ?>

<div id="loader-wrapper" style="display: flex; justify-content: center">
    <div id="loader" style="position: absolute;"></div>
    <div style="align-self: center;font-weight: 500">LOADING</div>
</div>

<?=$content?>
<?php $this->endBody() ?>
<?=data_get($this, 'blocks.scripts')?>
</body>
</html>
<?php $this->endPage() ?>
