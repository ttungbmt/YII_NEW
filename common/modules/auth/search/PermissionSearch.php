<?php
namespace common\modules\auth\search;


use common\modules\auth\models\AuthItem;
use yii\data\ActiveDataProvider;
use yii\rbac\Item;

class PermissionSearch extends AuthItem
{
    public function rules()
    {
        return [
            [['name', 'description'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'name' => 'Quyền truy cập'
        ]);
    }


    public function search($params)
    {
        $query = static::find()->andWhere(['type' => Item::TYPE_PERMISSION]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
             $query->where('0=1');
            return $dataProvider;
        }

        $query
            ->andFilterWhere(['ilike', 'name', $this->name])
            ->andFilterWhere(['ilike', 'description', $this->description]);

        return $dataProvider;
    }
}