<div class="sidebar-user-material">
    <div class="sidebar-user-material-body">
        <div class="card-body text-center">
            <a href="#">
                <img src="https://encrypted-tbn2.gstatic.com/images?q=tbn:ANd9GcSG6fwgVi4FtsNNn1lXnUwBmes8PCxp3-VlukOVNjq-byO7BJxXkg" class="img-fluid rounded-circle shadow-1 mb-3" width="80" height="80" alt="">
            </a>
            <h6 class="mb-0 text-white text-shadow-dark"><?=optional(userInfo())->fullname?></h6>
            <span class="font-size-sm text-white text-shadow-dark"><?=data_get(user(), 'identity.username')?></span>
        </div>

        <div class="sidebar-user-material-footer">
            <a href="#user-nav" class="d-flex justify-content-between align-items-center text-shadow-dark dropdown-toggle" data-toggle="collapse"><span><?=lang('My account')?></span></a>
        </div>
    </div>

    <div class="collapse" id="user-nav">
        <?=$this->render('_menu_user')?>
    </div>
</div>

<!--<div class="sidebar-user">
    <div class="category-content">
        <div class="media">
            <a href="#" class="media-left"><img src="https://encrypted-tbn2.gstatic.com/images?q=tbn:ANd9GcSG6fwgVi4FtsNNn1lXnUwBmes8PCxp3-VlukOVNjq-byO7BJxXkg" class="img-circle img-sm" alt=""></a>
            <div class="media-body">
                <span class="media-heading text-semibold">Victoria Baker</span>
                <div class="text-size-mini text-muted">
                    <i class="icon-pin text-size-small"></i> &nbsp;Santa Ana, CA
                </div>
            </div>

            <div class="media-right media-middle">
                <ul class="icons-list">
                    <li>
                        <a href="#"><i class="icon-cog3"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
-->