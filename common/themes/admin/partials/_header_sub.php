<?php
use yii\widgets\Breadcrumbs;
?>

<div class="breadcrumb-line">

    <div class="breadcrumb-line">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
    </div>


    {{--<ul class="breadcrumb-elements">
        <li><a href="#" class="legitRipple"><i class="icon-comment-discussion position-left"></i> Support</a></li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle legitRipple" data-toggle="dropdown">
                <i class="icon-gear position-left"></i>
                Settings
                <span class="caret"></span>
            </a>

            <ul class="dropdown-menu dropdown-menu-right">
                <li><a href="#"><i class="icon-user-lock"></i> Account security</a></li>
                <li><a href="#"><i class="icon-statistics"></i> Analytics</a></li>
                <li><a href="#"><i class="icon-accessibility"></i> Accessibility</a></li>
                <li class="divider"></li>
                <li><a href="#"><i class="icon-gear"></i> All settings</a></li>
            </ul>
        </li>
    </ul>--}}
</div>
