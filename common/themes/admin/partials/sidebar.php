<?php
use yii\bootstrap\Html;

$li = function ($name, $url, $icon = '', $rolename = null){
    $active = (app('request')->url == $url) ? 'active' : '';
//    if($rolename && !auth()->can($rolename)){
//        return null;
//    }

    return (
        Html::tag('li', (
            Html::a((
                Html::tag('i', null, ['class' => $icon]).
                Html::tag('span', $name)
            ), $url)
        ), ['class' => $active])
    );
}
?>

<div class="sidebar sidebar-main sidebar-default">
    <div class="sidebar-content">
        <?=$this->render('@app/views/partials/_sb_user')?>

        <!-- Main navigation -->
        <div class="sidebar-category sidebar-category-visible">
            <div class="category-content no-padding">
                <ul class="navigation navigation-main navigation-accordion">

                    <!-- Main -->
                    <li class="navigation-header"><span>Bảng tin</span> <i class="icon-menu" title="Bảng tin"></i></li>
                    <?=$li(lang('Dashboard'), '/admin/site/dashboard', 'icon-home4')?>
                    <?=$li(lang('Changelog').' <span class="label bg-blue-400">1.0</span></span>', '/admin/site/changelog', 'icon-list-unordered')?>

                    <li class="navigation-header"><span>Dịch tễ</span> <i class="icon-menu" title="Dịch tễ"></i></li>
                    <?=$li('Ca bệnh SXH', '/dieutra/sxh', 'icon-brain')?>
                    <?=$li('Ổ dịch', '/admin/odich-sxh', 'icon-cart4')?>

                    <li class="navigation-header"><span>Công cụ</span> <i class="icon-menu" title="Công cụ"></i></li>
                    <?=$li('Nhập dữ liệu excel', '/admin/excel/nhapcabenh', 'icon-database-insert', 'pcd.import-cabenh.*')?>

                    <?php if(can('pcd.thongke.*')):?>
                        <li class="navigation-header"><span>Thống kê & Báo cáo</span> <i class="icon-menu" title="Thống kê & Báo cáo"></i></li>
                        <?=$li('Thống kê', '/admin/thongke', 'icon-statistics', 'pcd.thongke.*')?>
                    <?php endif;?>

                    <?php if(role('admin')):?>
                        <li class="navigation-divider"></li>
                        <li class="navigation-header"><span>Danh mục</span> <i class="icon-menu" title="Danh mục"></i></li>
                        <?=$li('Loại bệnh', '/admin/loaibenh', 'icon-file-text', 'pcd.dm-benh.*')?>
                        <?=$li('Bệnh viện', '/admin/benhvien', 'icon-office', 'pcd.dm-benhvien.*')?>
                    <?php endif;?>

                    <?php if(hasRoles('admin')):?>
                        <li class="navigation-divider"></li>
                        <li class="navigation-header"><span>Quản trị người dùng</span> <i class="icon-menu" title="Quản trị người dùng"></i></li>
                        <?=$li('Người dùng &amp; Phân nhóm', '/auth/user', 'icon-user-tie', 'auth.*')?>
                        <?=$li('Nhóm người dùng', '/auth/role', 'icon-people', 'auth.*')?>
                        <?=$li('Phân quyền truy cập', '/auth/permission', 'icon-lock', 'auth.*')?>
                    <?php endif;?>


                    <li class="navigation-divider"></li>
                    <li class="navigation-header"><span><?=lang('Other')?></span> <i class="icon-menu" title="<?=lang('Other')?>"></i></li>
                    <li><a href="<?=url('/admin/site/contact')?>"><i class="icon-lifebuoy"></i> <span>Liên hệ</span></a> </li>
                    <li>
                        <a href=""><i class="icon-history"></i> <span>Lược sử</span></a>
                        <ul>
                            <li><a href="<?=url('/admin/log/user')?>">Đăng nhập</a></li>
                            <li><a href="<?=url('/admin/log/sxh')?>">Ca bệnh SXH</a></li>
                        </ul>
                    </li>


                    <li>
                        <a href="#"><i class="icon-book"></i> <span><?=lang('User guide')?></span></a>
                        <ul>
                            <?=$li('Tài liệu', '/admin/site/doc-guide')?>
                            <?=$li('Video', '/admin/site/video-guide')?>
                        </ul>
                    </li>

                    <?php if(hasRoles('admin')):?>
                        <li class="navigation-divider"></li>
                        <li class="navigation-header"><span><?=lang('Platform Administration')?></span> <i class="icon-menu" title="<?=lang('Platform Administration')?>"></i></li>
                        <li><a href=""><i class="icon-gear"></i> <span><?=lang('Settings')?></span></a></li>
                        <li>
                            <a href=""><i class="icon-puzzle2"></i> <span><?=lang('Appearance')?></span></a>
                            <!--                        <ul>-->
                            <!--                            <li><a href="#">@lang('Theme')</a></li>-->
                            <!--                            <li><a href="#">@lang('Menu')</a></li>-->
                            <!--                            <li><a href="#">@lang('Widget')</a></li>-->
                            <!--                            <li><a href="#">@lang('Theme Options')</a></li>-->
                            <!--                        </ul>-->
                        </li>
                        <!--                    <li><a href="{{url('cms/system-info')}}"><i class="icon-share4"></i> <span>@lang('System information')</span></a></li>-->
                        <!--                    <li><a href="{{url('cms/system-info')}}"><i class="icon-history"></i> <span>@lang('System logs')</span></a></li>-->
                        <!--                    <li><a href="{{url('cms/backups')}}"><i class="icon-cloud"></i> <span>@lang('Backup')</span></a></li>-->
                    <?php endif;?>
                </ul>
            </div>
        </div>
        <!-- /main navigation -->

    </div>
</div>