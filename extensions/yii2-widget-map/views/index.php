<?php
use yii\helpers\Html;
?>

<?=Html::beginTag('div', $options)?>
    <div class="progress-wrapper p-20">
        <div class="progress">
            <div class="progress-bar progress-bar-striped active" style="width: 100%">
                <span>Đang tải...</span>
            </div>
        </div>
    </div>
<?=Html::endTag('div')?>