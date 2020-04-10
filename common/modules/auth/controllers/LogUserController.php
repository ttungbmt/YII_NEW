<?php

namespace common\modules\auth\controllers;

use Yii;
use common\modules\auth\search\LogUserSearch;

class LogUserController extends AuthController
{

    public function actionIndex()
    {    
        $searchModel = new LogUserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
