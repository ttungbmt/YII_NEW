<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

/* @var $model \yii\db\ActiveRecord */
$model = new $generator->modelClass();
$enableMap = $generator->enableMap;
$enableHc = $generator->enableHc;
$displayHc = false;
$displayMap = false;
if($enableHc) {
    $class = $generator->modelClass;
    $columns = $class::getTableSchema()->getColumnNames();
    $quan = $generator->quan;
    $phuong = $generator->phuong;
    if(in_array($quan, $columns) && in_array($phuong, $columns)) {
        $displayHc = true;
    } else {
        $displayHc = false;
    }
}
if($enableMap) {
    $class = $generator->modelClass;
    $columns = $class::getTableSchema()->getColumnNames();
    if(in_array('geom', $columns)) {
        $displayMap = true;
    } else {
        $displayMap = false;
    }
}

$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

echo "<?php\n";
?>
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DepDrop;
use ttungbmt\map\Map;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */

$this->title = ($model->isNewRecord ? 'Thêm mới' : 'Cập nhật') . ' <?=$generator->title?>';
?>



<?= "<?php " ?>$form = ActiveForm::begin(); ?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form">

    <div class="card">

        <?php if($displayMap) : ?>
        <?= "<?=" ?> Map::widget(['model' => $model, 'attribute' => 'geom']) ?>
        <?php endif; ?>

        <div class="card-body">
            <?php foreach ($generator->getColumnNames() as $attribute) {
                if (in_array($attribute, $safeAttributes)) {
                    echo "    <?= " . $generator->generateActiveField($attribute) . " ?>\n\n";
                }
            } ?>

            <?php if($displayHc) : ?>
            <?= "<?=" ?> $form->field($model, 'maquan')->dropDownList(app('api')->get('dm_quan'), [
                'id' => 'maquan',
                'prompt' => 'Chọn quận...',
            ])->label('Quận') ?>

            <?= "<?=" ?> $form->field($model, 'maphuong')->widget(DepDrop::className(), [
                'options' => ['prompt' => 'Chọn phường...'],
                'pluginOptions' => [
                    'depends' => ['maquan'],
                    'url' => url(['/api/dm/phuong']),
                ],
            ])->label('Phường') ?>
            <?php endif; ?>


            <?='<?php if (!request()->isAjax): ?>'."\n"?>
            <div class="form-group">
                <?= "<?= " ?>Html::submitButton($model->isNewRecord ? lang('Create') : lang('Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
            <?="<?php endif; ?>\n"?>

            <?= "<?php " ?>ActiveForm::end(); ?>
        </div>
    </div>
</div>
