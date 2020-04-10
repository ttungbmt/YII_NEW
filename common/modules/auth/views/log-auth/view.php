<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\libs\activitylog\models\Activity */
?>
<div class="activity-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'log_name',
            'description',
            'subject_id',
            'subject_type',
            'causer_id',
            'causer_type',
            'properties:ntext',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
