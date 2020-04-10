<?php

namespace common\modules\auth\controllers;

use app\modules\auth\models\AuthUser;
use app\modules\auth\models\SearchAuthUser;
use app\services\UrlService;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * UserController implements the CRUD actions for AuthUser model.
 */
class RbacController extends Controller {
    public $layout = '@app/views/layouts/child_master';

    public function actionRole() {
        return $this->render('rbac', ['url' => Yii::$app->urlManager->createUrl('rbac/role')]);
    }

    public function actionRule() {
        return $this->render('rbac', ['url' => Yii::$app->urlManager->createUrl('rbac/rule')]);
    }

    public function actionAssignment() {
        return $this->render('rbac', ['url' => Yii::$app->urlManager->createUrl('rbac/assignment')]);
    }
}
