<?php

use yii\helpers\Html;

$tab = isset($tab) ? $tab : 'tab-userinfo';
?>
<div class="user-update">

    <?= $this->render('_form', [
        'model' => $model,
        'tab' => $tab,
        
    ]) ?>

</div>
