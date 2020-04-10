<?php
/**
 * This is the template for generating CRUD search class of the specified model.
 */

use yii\helpers\StringHelper;


/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
if ($modelClass === $searchModelClass) {
    $modelAlias = $modelClass . 'Model';
}
$rules = $generator->generateSearchRules();
$labels = $generator->generateSearchLabels();
$searchAttributes = $generator->getSearchAttributes();
$searchConditions = $generator->generateSearchConditions();
$enableHc = $generator->enableHc;
$displayHc = false;
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

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->searchModelClass, '\\')) ?>;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use <?= ltrim($generator->modelClass, '\\') . (isset($modelAlias) ? " as $modelAlias" : "") ?>;

/**
 * <?= $searchModelClass ?> represents the model behind the search form about `<?= $generator->modelClass ?>`.
 */
class <?= $searchModelClass ?> extends <?= isset($modelAlias) ? $modelAlias : $modelClass ?>

{

    public function rules()
    {
        return [
            <?= implode(",\n            ", $rules) ?>,
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        <?php if(!$displayHc) : ?>
        $query = <?= isset($modelAlias) ? $modelAlias : $modelClass ?>::find();
        <?php else: ?>
        $query = $this->find()
        ->alias('u')
        ->select(['u.*'])
        ->joinPhuong();
        <?php endif; ?>

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        <?php if($displayHc) : ?>
        $query->andFilterWhere([
            'u.maquan' => $this-><?=$quan?>,
            'u.maphuong' => $this-><?=$phuong?>,
        ]);
        <?php endif; ?>

        <?php //implode("\n        ", $searchConditions) ?>

        return $dataProvider;
    }
}
