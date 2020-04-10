<?php
use common\modules\notifications\widgets\Notifications;
?>
<div class="navbar navbar-expand-md navbar-dark bg-primary navbar-static" style="background-image: url(<?='/themes/admin/custom/img/bg-polygon.png'?>)">
    <?=$this->render('_navbar_header')?>

    <div class="d-md-none">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
            <i class="icon-tree5"></i>
        </button>
        <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
            <i class="icon-paragraph-justify3"></i>
        </button>
    </div>

    <div class="collapse navbar-collapse" id="navbar-mobile">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
                    <i class="icon-paragraph-justify3"></i>
                </a>
            </li>
        </ul>
        <span class="navbar-text ml-md-3" style="text-align:center!important; text-transform: uppercase!important; font-weight:bold!important;color: #FEF15E;text-shadow: 2px 2px 2px rgba(150, 150, 150, 1);font-size: 20px; padding: 0"><?=params('app_name')?></span>

        <ul class="navbar-nav ml-auto">
            <?=Notifications::widget([

            ])?>

            <li class="nav-item">
                <a href="#" class="navbar-nav-link">
                    Xin chào
                </a>
            </li>

            <li class="nav-item">
                <a href="<?=url('/logout')?>" class="navbar-nav-link" data-popup="tooltip" data-original-title="<?=lang('Logout')?>">
                    <i class="icon-switch2"></i>
                    <span class="d-md-none ml-2"><?=lang('Logout')?></span>
                </a>
            </li>
        </ul>
    </div>
</div>