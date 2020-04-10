<?php
namespace common\modules\auth\controllers;


use common\modules\auth\models\AuthItem;
use common\modules\auth\search\PermissionSearch;
use yii\rbac\Item;

class PermissionController extends AuthController
{
    protected $modelClass = 'common\modules\auth\models\AuthItem';

    public function actionIndex(){
        $searchModel = new PermissionSearch();
        $dataProvider = $searchModel->search(app('request.queryParams'));

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new AuthItem();
        if ($model->load(request()->post()) && $model->savePerm()) {
            return $this->redirect(['view', 'id' => $model->name]);
        } else {
            return $this->render('_form', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findRole($id);

        if ($model->load(request()->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->name]);
        } else {
            return $this->render('_form', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($id)
    {
        $this->findRole($id)->delete();

        if(request()->isAjax) return $this->renderJson(['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax']);

        return $this->redirect(['index']);
    }

    public function actionView($id){
        $model = $this->findModel($id);

        return $this->render('view', compact('model'));
    }

    protected function restSave(&$model)
    {
        $model->save();
    }

    protected function findRole($id){
        return AuthItem::find()->where(['type' => Item::TYPE_PERMISSION, 'name' => $id])->one();
    }
}