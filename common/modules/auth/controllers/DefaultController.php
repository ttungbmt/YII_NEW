<?php

namespace common\modules\auth\controllers;

use common\modules\auth\forms\LoginForm;
use common\modules\auth\forms\RegisterForm;
use Yii;
use yii\web\Controller;

/**
 * Default controller for the `auth` module
 */
class DefaultController extends Controller
{
    public $layout = '@app/views/layouts/auth';

    public $defaultAction = 'login';

    public function actionLogin(){
        if (!app('user')->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(app('request')->post()) && $model->login()) {
            return $this->goBack();
//            $returnUrl = params('loginReturnUrl', '/');
//            return $this->redirect($returnUrl);
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout(){
        app('user')->logout();
        return $this->goHome();
    }

    public function actionRegister(){
        $model = new RegisterForm();

        return $this->render('register', ['model' => $model]);
    }

    public function actionResetPassword(){
        return $this->render('reset_pwd');
    }

    public function actionForgetPassword(){
        return $this->render('forget_pwd');
    }

    public function actionUnlock(){
        return $this->render('unlock');
    }
}
