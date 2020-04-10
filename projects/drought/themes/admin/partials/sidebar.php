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
                <!-- Main -->
                <li class="nav-item-header">
                    <div class="text-uppercase font-size-xs line-height-xs">Bảng tin</div> <i class="icon-menu" title="Bảng tin"></i>
                </li>
                <?= $li(lang('Dashboard'), '/admin', 'icon-home4') ?>
                <li class="nav-item"><a href="<?= '/admin/site/changelog' ?>" class="nav-link"><i class="icon-list-unordered"></i><span><?= lang('Changelog') ?></span><span class="badge bg-blue-400 align-self-center ml-auto">2.0</span></a></li>
                <?= $li(lang('Contact'), '/admin/site/contact', 'icon-phone') ?>

                <li class="nav-item-header">
                    <div class="text-uppercase font-size-xs line-height-xs">Quản lý</div> <i class="icon-menu" title="Quản lý"></i>
                </li>
                <?= $li('Thư viện ảnh', '/admin/gallery', 'icon-images2') ?>
                <?= $li('Xử lý ảnh', '/admin/raster-calc', 'icon-image-compare') ?>

                <?php if (role('admin')) : ?>
                    <li class="nav-item-header">
                        <div class="text-uppercase font-size-xs line-height-xs">Quản trị hệ thống</div> <i class="icon-menu" title="Quản trị hệ thống"></i>
                    </li>
                    <?= $li('Người dùng', '/auth/user', 'icon-user-tie') ?>
                    <?= $li('Phân quyền người dùng', '/auth/role', 'icon-people') ?>
                    <?= $li('Phân quyền truy cập', '/auth/permission', 'icon-lock') ?>
                <?php endif; ?>

                <li class="nav-item-header">
                    <div class="text-uppercase font-size-xs line-height-xs">Khác</div> <i class="icon-menu" title="Khác"></i>
                </li>
                <li class="nav-item nav-item-submenu nav-item-expanded">
                    <a href="#" class="nav-link"><i class="icon-history"></i> <span>Lược sử </span></a>
                    <ul class="nav nav-group-sub" data-submenu-title="Lược sử">
                        <?= $li('Truy cập', '/auth/log-auth') ?>
                        <?= $li('Thao tác', '/auth/log-user') ?>
                    </ul>
                </li>
                <!-- /main -->

            </ul>
        </div>
        <!-- /main navigation -->

    </div>
    <!-- /sidebar content -->
</div>