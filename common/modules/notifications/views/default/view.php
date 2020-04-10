<div class="nav-item dropdown">
    <a href="#" class="navbar-nav-link dropdown-toggle caret-0" data-toggle="dropdown">
        <i class="icon-bell2"></i>
        <?php if(count($notifications) > 0):?>
            <span class="badge badge-pill bg-warning-400 ml-auto ml-md-0"><?=count($notifications)?></span>
        <?php endif; ?>
    </a>

    <div id="notification-box-content" class="dropdown-menu dropdown-menu-right dropdown-content wmin-md-350">
        <div class="dropdown-content-header border-bottom-grey">
            <span class="font-size-lg font-weight-semibold">Thông báo</span>
            <a href="<?=url(['/notifications'])?>" class="text-default" data-popup="tooltip" title="Tìm kiếm"><i class="icon-search4 font-size-base"></i></a>
        </div>

        <div class="dropdown-content-body dropdown-scrollable">
            <ul class="media-list">
                <?php if(count($notifications) > 0):?>
                    <?php foreach ($notifications as $m):?>
                        <li class="media act-read" data-read-url="<?=url(['/notifications/default/read', 'id' => $m->id])?>" data-go-url="<?=$m->url?>">
                            <div class="mr-3">
                                <div class="btn bg-success-400 rounded-round btn-icon"><i class="icon-aid-kit"></i></div>
                            </div>

                            <div class="media-body">
                                <div class="<?=$m->isRead() ? 'text-muted' : ''?>"><?=$m->subject?></div>
                                <div class="font-size-sm text-muted mt-1" title="<?=$m->createdAtText?>"><?=$m->createdAtDiffText?></div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="p-2">Không có thông báo</div>
                <?php endif; ?>
            </ul>
        </div>

        <div class="dropdown-content-footer bg-light">
            <a href="<?=url(['/notifications'])?>" class="font-weight-semibold mr-auto">Xem tất cả</a>
            <div>
                <a href="<?=url(['/notifications/default/read-all'])?>" class="text-grey" data-popup="tooltip" title="Đánh dấu tất cả là đã đọc"><i class="icon-checkmark3"></i></a>
                <a href="<?=url(['/notifications/default/settings'])?>" class="text-grey ml-2" data-popup="tooltip" title="Cài đặt"><i class="icon-gear"></i></a>
            </div>
        </div>
    </div>
</div>



