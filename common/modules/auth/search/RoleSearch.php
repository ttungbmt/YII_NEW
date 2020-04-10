<?php
namespace common\modules\auth\search;


use common\modules\auth\models\AuthItem;
use yii\data\ActiveDataProvider;
use yii\rbac\Item;

class RoleSearch extends AuthItem
{
    public function rules()
    {
        return [
            [['name', 'description'], 'string'],
        ];
    }

    public function search($params)
    {
        $query = AuthItem::find()->andWhere(['type' => Item::TYPE_ROLE]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query
            ->andFilterWhere(['ilike', 'name', $this->name])
            ->andFilterWhere(['ilike', 'description', $this->description]);

        return $dataProvider;
    }
}