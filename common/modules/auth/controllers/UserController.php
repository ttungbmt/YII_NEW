<?php

namespace common\modules\auth\controllers;

use common\models\User;
use common\modules\auth\forms\UserForm;
use common\modules\auth\search\UserSearch;
use ttungbmt\noty\Noty;
use yii\helpers\Html;

class UserController extends AuthController
{
    protected $modelClass = 'common\models\User';

    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(app('request.queryParams'));

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionView($id)
    {
        if (request()->isAjax) {
            return $this->renderJson([
                'title'   => "User #" . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer'  => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
            ]);
        } else {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    public function actionCreate()
    {
        $model = new UserForm();

        if ($model->load($data = request()->post()) && $model->saveUser($data)) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = UserForm::findOrFail($id);

        if ($model->saveUser()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($id)
    {
        $model = UserForm::findOne($id);

        if ($model->saveNewPass()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'tab'   => 'tab-resetpass'
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
//        !$model->info || $model->info->delete();
        $model->delete();

        if (request()->isAjax) return $this->renderJson(['forceClose' => true, 'forceReload' => '#crud-datatable-pjax']);

        return $this->redirect(['index']);
    }

    public function actionLoginAsUser($id)
    {
        $user = User::findOne($id);
        session(['roles' => []]);
        session()->setFlash(Noty::TYPE_SUCCESS, 'Bạn đã đăng nhập với tư cách thành viên');
        \Yii::$app->user->switchIdentity($user, 0);
        return $this->redirect(['/']);
    }
}
