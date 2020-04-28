<?php

use yii\bootstrap\Html;

$li = function ($name, $url, $icon = '', $access = null) {
    $active = (app('request')->url == $url) ? 'active' : '';
    if ($access && !can($access)) {
        return null;
    }

    return (Html::tag('li', (Html::a((
        (empty($icon) ? '' :  Html::tag('i', null, ['class' => $icon])) .
        Html::tag('span', $name)), $url, ['class' => 'nav-link'])), ['class' => 'nav-item ' . $active]));
}
?>
<style>
    .view-console {
        overflow: auto;
        background-color: #343a40;
        color: #fff;
        padding: 10px;
        border-radius: 0;
        white-space: nowrap;
    }
    .modal-title { position: absolute}

</style>


<div class="sidebar sidebar-light sidebar-main sidebar-expand-md">

    <!-- Sidebar mobile toggler -->
    <div class="sidebar-mobile-toggler text-center">
        <a href="#" class="sidebar-mobile-main-toggle">
            <i class="icon-arrow-left8"></i>
        </a>
        Navigation
        <a href="#" class="sidebar-mobile-expand">
            <i class="icon-screen-full"></i>
            <i class="icon-screen-normal"></i>
        </a>
    </div>
    <!-- /sidebar mobile toggler -->

    <!-- Sidebar content -->
    <div class="sidebar-content">

        <!-- User menu -->
        <?= $this->render('@app/views/partials/_sb_user') ?>
        <!-- /user menu -->

        <!-- Main navigation -->
        <div class="card card-sidebar-mobile">
            <ul class="nav nav-sidebar" data-nav-type="accordion">
                <li class="nav-item-header">
                    <div class="text-uppercase font-size-xs line-height-xs">Quản lý</div> <i class="icon-menu" title="Quản lý"></i>
                </li>
                <?= $li('Ảnh đầu vào', '/admin/gallery', 'icon-images2') ?>
                <?= $li('Tính toán CDI', '/admin/raster-calc', 'icon-image-compare') ?>


            </ul>
        </div>
        <!-- /main navigation -->

    </div>
    <!-- /sidebar content -->
</div>