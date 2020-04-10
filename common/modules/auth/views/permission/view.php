<?php
$this->title = 'Chi tiết quyền truy cập';
use kartik\detail\DetailView;
?>
<?=DetailView::widget([
    'model' => $model,
    'attributes' => [
        'name',
        'description',
    ]
])?>