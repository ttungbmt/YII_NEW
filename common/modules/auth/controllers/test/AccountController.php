<?php
namespace common\modules\auth\controllers;

use app\modules\auth\models\AuthUser;
use app\modules\cms\controllers\AdminController;

class AccountController extends AdminController
{
    public function actionUpdate($id){
        $model = AuthUser::findOne($id);
        $post = $this->request->post();
        if($_POST
        ){
            $model->load($post);
            $model->info->load($post);

            $model->save();
            $model->info->save();
        }

        return $this->render('update', compact('model'));
    }

    public function actionProfile(){
        return $this->render('profile');
    }

    public function actionHistory(){
        return $this->render('history');
    }
}