<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model covid\models\Vung */

$this->title = Yii::t('app', 'Create Vung');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Vungs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vung-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
