<?php
$this->title = 'Video HDSD';

?>

<?php foreach (collect($media)->chunk(3) as $chunk):?>
    <div class="row">
        <?php foreach ($chunk as $k => $media):?>
            <div class="col-lg-4 col-sm-6">
                <div class="card">
                    <div class="video-container">
                        <div class="embed-responsive embed-responsive-16by9">
                            <video controls>
                                <source src="<?= url(data_get($media, 'url')) ?>" type="video/mp4">
                            </video>
                        </div>
                    </div>

                    <div class="card-body">
                        <h6 class="no-margin-top font-weight-semibold card-title"><a href="#" class="text-default"><?=($k+1).') '.data_get($media, 'title')?></a> <a href="#" class="text-muted"><i class="icon-cog5 pull-right"></i></a></h6>
                        <?=data_get($media, 'content')?>
                    </div>
                </div>
            </div>
        <?php endforeach;?>
    </div>
<?php endforeach;?>



