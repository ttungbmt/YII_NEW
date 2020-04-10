<?php
/**
 * Created by PhpStorm.
 * User: THANHTUNG
 * Date: 27-Dec-17
 * Time: 11:23 AM
 */

namespace common\modules\auth\controllers;

use common\modules\auth\models\AuthItem;
use common\modules\auth\search\RoleSearch;
use yii\rbac\Item;
use Yii;

class RoleController extends AuthController
{
    public function actionIndex(){
        $searchModel = new RoleSearch();
        $dataProvider = $searchModel->search(app('request.queryParams'));

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new AuthItem();

        if(request()->post()) {
            $model->type = Item::TYPE_ROLE;
        }

        if ($model->saveRoles()) {
            return $this->redirect(['index']);
        }

        return $this->render('_form', ['model' => $model]);
    }


    public function actionUpdate($id)
    {
        $model = $this->findRole($id);

        if ($model->saveRoles()) {
            return $this->redirect(['index']);
//            return $this->redirect(['view', 'id' => $model->name]);
        }

        return $this->render('_form', ['model' => $model]);
    }


    public function actionDelete($id)
    {
        $this->findRole($id)->delete();

        if(request()->isAjax) return $this->renderJson(['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax']);

        return $this->redirect(['index']);
    }

    protected function findRole($id){
        return AuthItem::find()->where(['type' => Item::TYPE_ROLE, 'name' => $id])->one();
    }

}