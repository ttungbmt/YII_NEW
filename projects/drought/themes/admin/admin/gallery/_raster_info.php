<?php
use ttungbmt\gdal\Gdal;

$output = null;
$source = $model->getUploadPath();
if(file_exists($source)){
    $process = new Gdal();
    $process->gdalinfo($source);
    $output = $process->run();
};
?>
<?php if (file_exists($source)): ?>
    <div class="card">
        <div class="badge bg-blue-400 font-weight-semibold text-uppercase" style="font-size: 15px">Thông tin ảnh</div>
        <pre class="view-console" style="border-top-width: 0;">
        <?= viewConsole($output) ?>
    </pre>
    </div>
<?php endif; ?>
