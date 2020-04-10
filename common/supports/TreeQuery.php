<?php
namespace common\supports;

use common\models\MyQuery;
use creocoder\nestedsets\NestedSetsQueryBehavior;

class TreeQuery extends MyQuery
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }
}