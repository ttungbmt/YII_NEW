<?php

namespace common\modules\auth\controllers;

use app\modules\auth\models\LoginForm;
use app\modules\auth\models\NguoiNopHS;
use app\modules\auth\models\Taikhoan;
use app\services\AuthUserService;
use app\services\UtilsService;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

/**
 * This class for manager user if user not administrator role
 * @author TriLVH
 */
class TaikhoanController extends Controller {

    public $layout = '@app/views/layouts/child_master';
    /**
     * This function init TEST VALUE
     */
    public function init() {
        
    }

    /**
     * This function for login user
     * @return type
     */
    public function actionDangnhap() {
        if (!\Yii::$app->user->isGuest) {
            return $this->redirect(Yii::$app->urlManager->createUrl('home'));
        }

        $request = Yii::$app->request;
        $model = new LoginForm();
        if ($request->isPost && $model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(Yii::$app->urlManager->createUrl('home'));
        } else {
            if ($request->isAjax) {
                return $this->renderAjax('dangnhap', ['model' => $model]);
            } else {
                $this->layout = 'login';
                return $this->render('dangnhap', ['model' => $model]);
            }
        }
    }

    /**
     * This function for register new user
     * @return type
     */
    public function actionDangky() {
        $request = Yii::$app->request;
        $redirect = ArrayHelper::getValue($request->get(), 'redirect', 'home');

        $model = new NguoiNopHS();

        if ($request->isPost && $model->load($request->post()) && $model->save()) {
            return $this->redirect(Yii::$app->urlManager->createUrl($redirect));
        }

        if ($request->isAjax && $request->isGet) {
            return $this->renderAjax('dangky', ['model' => $model]);
        } else {
            $this->layout = 'login';
            return $this->render('dangky', ['model' => $model]);
        }
    }

    /**
     * This function to update user information by user logged.
     * @return type
     */
    public function actionCapnhat() {
        $request = Yii::$app->request;

        $model = Taikhoan::findOne(Yii::$app->user->id);

        if ($request->isGet) {
            return $this->render('capnhat', ['model' => $model]);
        } else if ($request->isPost && $model->load($request->post()) && $model->save()) {
            UtilsService::pushMessage(UtilsService::$_M_SUCCESS, 'Cập nhật thông tin thành công');
            return $this->redirect(Yii::$app->urlManager->createUrl('dichte/cabenh/index'));
        } else {
            UtilsService::pushMessage(UtilsService::$_M_ERROR, 'Vui lòng kiểm tra dữ liệu');
            return $this->render('capnhat', ['model' => $model]);
        }
    }

    public function actionLichsu() {
        return $this->render('lichsu');
    }

    /**
     * This function to check if user not logged in, redirect to site/login page, controller for logged only
     * @param type $action
     * @return type
     */
//    public function beforeAction($action) {
//        if (!AuthUserService::isGuest() & !$action == 'capnhat') {
//            return parent::beforeAction($action);
//        } else {
//            return $this->redirect(Yii::$app->urlManager->createUrl('site/login'));
//        }
//    }
}
