<ul class="nav nav-sidebar">
    <li class="nav-item"><a href="<?=url(['/auth/user/update', 'id' => user()->getId()])?>" class="nav-link"><i class="icon-user-plus"></i> <span><?=lang('My profile')?></span></a></li>
    <li class="divider"></li>
    <?php if(role('admin')):?>
        <li class="nav-item"><a href="#" class="nav-link"><i class="icon-cog5"></i> <span><?=lang('Settings')?></span></a></li>
    <?php endif;?>
    <li class="nav-item"><a href="<?=url('/logout')?>" class="nav-link"><i class="icon-switch2"></i> <span><?=lang('Logout')?></span></a></li>
</ul>
