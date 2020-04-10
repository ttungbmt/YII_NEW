<?php
use drought\support\PgCommand;
$this->title = 'Chi tiết: '.$model->name;
$cmd = new PgCommand();
$out = $cmd->getInfo($model->getUploadUrl());
$outStr = collect($out)->map(function ($i){
   return str_replace(' ', '&nbsp;', $i);
})->implode(' <br />');
?>

<div class="card">
    <div class="card-body">
        <code>
            <?= $outStr; ?>
        </code>
    </div>
</div>
