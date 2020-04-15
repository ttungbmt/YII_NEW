<?php

use ttungbmt\gdal\Gdal;

$output = null;
$source = $model->getFileCalcPath();
if ($source) {
    $process = new Gdal();
    $process->gdalinfo($source);
    $output = $process->run();
};
?>
<?php if ($source): ?>
    <div class="card" style="width: 100%">
        <div class="badge bg-blue-400 font-weight-semibold text-uppercase" style="font-size: 15px">Thông tin ảnh
        </div>
    <pre class="view-console" style="border-top-width: 0; height: 350px">
        <?= viewConsole($output) ?>
    </pre>
    </div>
<?php endif; ?>
