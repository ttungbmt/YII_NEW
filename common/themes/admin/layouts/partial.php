<?php
//use common\assets\AppPluginAsset;
//AppPluginAsset::register($this);
//\common\assets\BeginAsset::register($this);
//\common\assets\EndAsset::register($this);
Yii::$app->log->targets['debug'] = null;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?=app('language')?>">
<head>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<?=$content?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
