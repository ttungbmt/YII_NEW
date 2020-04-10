<?php
use ttungbmt\gdal\Gdal;

$output = null;
$source = $model->getUploadPath();
if($source){
    $process = new Gdal();
    $process->gdalinfo($source);
    $output = $process->run();
};
?>
<?php if ($source): ?>
    <div class="card">
        <div class="badge bg-blue-400 font-weight-semibold text-uppercase" style="font-size: 15px">Raster Infomation</div>
        <pre class="view-console" style="border-top-width: 0;">
        <?= viewConsole($output) ?>
    </pre>
    </div>
<?php endif; ?>
