<?php
use yii\widgets\DetailView;
use drought\support\PgCommand;
$this->title = 'Chi tiết: '.$model->name;
$cmd = new PgCommand();
$out = $cmd->getInfo($model->getUploadFile());
$outStr = collect($out)->map(function ($i){
    return str_replace(' ', '&nbsp;', $i);
})->implode(' <br />');
?>

<?php

?>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'image',
        'name',
        'code',
        'date',
    ],
]) ?>
<hr>
<h4 class="badge badge-light badge-striped badge-striped-left border-left-info mt-2" style="font-size: 13px">Meta file</h4>
<br>
<code>
    <?= $outStr; ?>
</code>
