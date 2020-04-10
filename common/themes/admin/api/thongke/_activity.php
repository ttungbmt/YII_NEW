<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 7/30/2018
 * Time: 2:32 PM
 */
?>
<!-- Đăng nhập gần đây -->
<div class="card card-white">
    <div class="card-header">
        <h6 class="card-title text-semibold">
            <i class="icon-enter2 position-left"></i>
            Đăng nhập gần đây
        </h6>
    </div>
    <div class="card-body">
        <div class="list-feed">
            <?php if (isset($activity_log)) {
                foreach ($activity_log as $user) { ?>
                    <div class="list-feed-item border-warning-400">
                        <a href="/auth/user/view?id=<?= $user['causer_id'] ?>"><?= $user['username']; ?></a>
                        <div class="text-muted text-size-small mb-1"><?= $user['recent_time'] ?></div>
                    </div>
                <?php }
            } ?>
        </div>
    </div>
</div>
