<?php
//use app\modules\role_phuongquan\services\PQService;
use common\assets\MapAsset;
use yii\helpers\Url;

MapAsset::register($this);
//$roles = PQService::getRolePQOfCurrentUser();
//$currentUser = $roles ? $roles->attributes : null;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= app()->language ?>">
<head>
    <?=$this->render('../partials/meta')?>
    <title> <?=$this->title?> | <?=params('app_name')?></title>
    <?php $this->head() ?>

    <script>
        window.BASE_URL = '<?= Url::home(true); ?>';
        window.url_home = (str) => {
            let uri = new URI(window.BASE_URL);
            uri.segment(str);
//            !str ||  uri.pathname('web/' + str);
            return decodeURIComponent(uri.normalize().toString());
        }
//        window.currentUser = JSON.parse('<?//= json_encode($currentUser) ?>//');
        window.ee = new EventEmitter();
    </script>
</head>
<body>
<?php $this->beginBody() ?>

<div id="loader-wrapper" style="display: flex; justify-content: center">
    <div id="loader" style="position: absolute;"></div>
    <div style="align-self: center;font-weight: 500">Loading</div>
</div>

<?= $content ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

