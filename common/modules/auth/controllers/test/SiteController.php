<?php

namespace common\modules\auth\controllers;

use app\models\ContactForm;
use app\models\LoginForm;
use app\models\QlkhDetai;
use Yii;
use yii\web\Controller;

class SiteController extends Controller {

    public function behaviors() {
        return [
//            'access' => [
//                'class' => AccessControl::className(),
//                'only' => ['logout'],
//                'rules' => [
//                    [
//                        'actions' => ['logout'],
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ],
//                ],
////            ],
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'logout' => ['post'],
//                ],
//            ],
        ];
    }

    public function actionTest() {
        return json_encode(QlkhDetai::primaryKey());
    }

    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }


    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->redirect(Yii::$app->urlManager->createUrl('home'));
    }

}
