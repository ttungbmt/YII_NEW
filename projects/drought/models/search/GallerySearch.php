<?php

namespace drought\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use drought\models\Gallery as GalleryModel;

/**
 * Gallery represents the model behind the search form about `drought\models\Gallery`.
 */
class GallerySearch extends GalleryModel
{

    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['image', 'name', 'code', 'date', 'created_at', 'updated_at', 'type', 'folder', 'year'], 'safe'],
        ];
    }

    public function formName()
    {
        return '';
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = GalleryModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, '');

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'code' => $this->code,
            'type' => $this->type,
            'folder' => $this->folder,
            'year' => $this->year,
        ]);

        $query->andFilterWhere(['ilike', 'code', trim($this->name)]);

        return $dataProvider;
    }
}
