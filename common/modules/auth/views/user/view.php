<?php
use yii\bootstrap\Html;
use yii\widgets\DetailView;
$this->title = 'Chi tiết người dùng';
?>
<div class="user-view">

    <div class="card">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'username',
                'email:email',
                'status',
                'created_at',
                'updated_at',
            ],
        ]) ?>
    </div>
    <?php if (!Yii::$app->request->isAjax): ?>
        <div class="form-group">
            <?= Html::a(lang('Edit'), ['/auth/user/update', 'id' => $model->getId()],['class' => 'btn btn-primary']) ?>
        </div>
    <?php endif; ?>
</div>



