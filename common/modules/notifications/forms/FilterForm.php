<?php

namespace common\modules\notifications\forms;

use common\modules\notifications\models\Notification;
use pcd\supports\RoleHc;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class FilterForm extends Notification
{
    public $is_seen;
    public $date_from;
    public $date_to;

    public function rules()
    {
        return [
            [['is_seen'], 'safe'],
            [['is_seen'], 'default', 'value' => []],
            [['date_from', 'date_to'], 'date', 'format' => 'd/m/Y']
        ];
    }

    public function attributeLabels()
    {
        return [
            'is_seen'   => 'Tình trạng',
            'date_from' => 'Từ ngày',
            'date_to'   => 'Đến ngày',
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Notification::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'       => ['defaultOrder' => ['id' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
             $query->where('0=1');
            return $dataProvider;
        }

        if(count($this->is_seen) == 1){
            $i = array_intersect($this->is_seen, [2]);
            if(empty($i)){
                $query->andWhere('read_at IS NULL');
            } else {
                $query->andWhere('read_at IS NOT NULL');
            }
        }

        $query->andFilterDate(['created_at' => [$this->date_from, $this->date_to]]);

        $query->andFilterWhere(['notifiable_id' => !role('admin') ? user()->id : null]);

        return $dataProvider;
    }
}