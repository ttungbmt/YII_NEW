<?php
use yii\helpers\Html;
use kartik\alert\Alert;

$errorOptions = [
    'class'  => 'alert alert-danger no-border m-10',
    'header' => (
        '<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>' .
        '<p class="font-weight-semibold">Vui lòng sửa các lỗi sau đây: </p>'
    )
];
$collectErrors = function ($models, $showAllErrors){
    $lines = [];
    if (!is_array($models)) {
        $models = [$models];
    }

    foreach ($models as $model) {
        $lines = array_unique(array_merge($lines, $model->getErrorSummary($showAllErrors)));
    }

    return array_values($lines);
};
$models = isset($models) ? collect($models) : collect();
$summaryErrors = $collectErrors($models->all(), false);
$errors = $models->map(function ($i){return $i->getErrors();})->filter();
?>
<?php if(isset($messages) && is_string($messages)) :?>
    <div class="card-body" style="padding: 10px;">
        <?= Alert::widget([
            'type' => Alert::TYPE_WARNING,
            'title' => 'Lỗi file excel!',
            'icon' => 'glyphicon glyphicon-exclamation-sign',
            'showSeparator' => true,
            'body'    => $messages,
        ]) ?>
    </div>
<?php else: ?>
    <?php if(!empty($summaryErrors)): ?>
        <div class="alert alert-danger border-0 alert-dismissible m-2">
            <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
            <span class="font-weight-semibold">Vui lòng sửa các lỗi sau đây trên file nhập liệu:</span>.
            <ul>
                <?php foreach ($summaryErrors as $k => $e):?>
                    <li><?='Cột '.$e?></li>
                <?php endforeach;?>
            </ul>
            <u><a class="font-weight-semibold" data-toggle="collapse" data-target="#v-detail-errors" style="cursor: pointer; ">Xem chi tiết</a></u>
            <div id="v-detail-errors" class="collapse">
                <ul>
                    <?php foreach ($errors as $k => $e):?>
                        <?php $val = data_get($models, "{$k}.".head(array_keys($e))); ?>
                        <li><?="Dòng ".($k+1).": ".collect($e)->map(function ($i){return head($i);})->flatten()->implode(', ')?> (<u style="background: #ff7043"><?=$val?></u>)</li>
                    <?php endforeach;?>
                </ul>
            </div>
        </div>
    <?php endif;?>
<?php endif; ?>

<style>
    .fade:not(.show){opacity: 1 !important;}
</style>
