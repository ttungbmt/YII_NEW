<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $generator johnitvn\ajaxcrud\generators\Generator */
?>

<h3>General Configuration</h3>


<?=$form->field($generator, 'title')?>
<?=$form->field($generator, 'modelClass')?>
<?=$form->field($generator, 'searchModelClass')?>
<?=$form->field($generator, 'controllerClass')?>
<?=$form->field($generator, 'viewPath')?>
<?=$form->field($generator, 'baseControllerClass')?>

<?php
//$class = $generator->modelClass;
//if($class){
//    $model = $class::getTableSchema();
//    dd($model);
//}
?>
<div class="row">
    <div class="col-md-6">
        <?=$form->field($generator, 'enableAjax')->checkbox()?>
    </div>
    <div class="col-md-6">
        <?=$form->field($generator, 'enableMap')->checkbox()?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <?=$form->field($generator, 'enableI18N')->checkbox()?>
    </div>
    <div class="col-md-6">
        <?=$form->field($generator, 'enableHc')->checkbox(['id' => 'checkHc'])?>
    </div>
</div>

<?=$form->field($generator, 'messageCategory')?>

<div class="row" id="divHc" style="display: none;">
    <div class="col-md-6">
        <?=$form->field($generator, 'quan')->textInput(['placeholder' => 'Column Name'])?>
    </div>
    <div class="col-md-6">
        <?=$form->field($generator, 'phuong')->textInput(['placeholder' => 'Column Name'])?>
    </div>
</div>


<script type="text/javascript">
    document.getElementById('divHc').style.display = document.getElementById('checkHc').checked ?  'block' : 'none';
    document.getElementById('checkHc').onclick = function () {
        let divHc = document.getElementById('divHc');
        divHc.style.display = this.checked ? 'block' : 'none';
    }
</script>


