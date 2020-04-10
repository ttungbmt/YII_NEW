<?php

namespace common\modules\auth\search;

use common\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class UserSearch extends User
{
    public $maquan;
    public $maphuong;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'auth_key', 'password_hash', 'password_reset_token', 'email'], 'safe'],
            [['maphuong', 'maquan'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
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
        $query = User::find();

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => [
                'defaultPageSize' => 10,
            ],
            'sort'       => ['defaultOrder' => ['id' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith([
            'info.hcPhuong' => function ($query) {
                $query->andFilterWhere(['hc_phuong.maphuong' => $this->maphuong]);
            },
            'info.hcQuan'   => function ($query) {
                $query->andFilterWhere(['hc_quan.maquan' => $this->maquan]);
            }
        ]);

        $query->andFilterWhere([
            'user.status'     => $this->status,
        ]);

        $query->andFilterWhere(['like', 'user.username', $this->username])
            ->andFilterSearch(['like', 'user.email', $this->email]);

        return $dataProvider;
    }
}
