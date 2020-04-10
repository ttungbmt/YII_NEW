<?php

namespace common\modules\auth\controllers;

use common\modules\auth\search\UserSearch;

use common\controllers\BackendController;
use Yii;
use yii\filters\VerbFilter;

class UserController extends BackendController
{
//    public $layout = '@pcd/layouts/master';

//    public $modalName = 'Người dùng';

    public function actionLoginWithUser($username){
        if(user()->is('admin')){
            $user = AuthUser::findByUsername($username);
            Yii::$app->user->login($user, 3600*24*30);
            return redirect(url_home('dieutra/sxh'));
        }
    }

    /**
     * Lists all AuthUser models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(app('request.queryParams'));

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->view('view');
    }

    public function actionCreate(){
        return $this->CreateUpdate();
    }

    public function actionUpdate($id)
    {
        return $this->CreateUpdate($id);
    }

    protected function CreateUpdate($id = null){
        $model = $id ? $this->findModel($id) : new AuthUser();
        $info = $model->info ?: new UserInfo();


        if(request()->isPost
            && $model->saveRelation(request()->post(), $info)
        ){
            $this->modalPasses = true;

            if(user()->can('auth.*')){
                return $this->redirect(['index']);
            }
            return $this->refresh();
        }

        return $this->view('form', [
            'model' => $model,
            'info' => $info,
        ]);
    }

    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $model->delete();
        $model->unlinkAll('roles', true);
        $model->unlinkAll('info', true);

        $px = RolePhuongquan::find()->where(['user_id' => $id])->one()->delete();

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>true];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }


    }


    /**
     * Finds the AuthUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AuthUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AuthUser::find()->joinWith('info')->with(['roles'])->where(['auth_user.id' => $id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app','The requested page does not exist.'));
        }
    }
}
