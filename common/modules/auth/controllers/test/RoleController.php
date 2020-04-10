<?php

namespace common\modules\auth\controllers;

use app\modules\auth\models\AuthAssignment;
use app\modules\auth\models\AuthItemChild;
use app\modules\auth\models\Role;
use app\modules\auth\models\RoleSearch;
use app\modules\auth\services\AuthService;
use app\modules\auth\services\UserService;
use app\modules\pcd\controllers\PcdController;
use Closure;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\rbac\DbManager;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Html;

class RoleController extends PcdController
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return parent::behaviors();
    }

    /**
     * Lists all Role models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $user = new UserService();
        $searchModel = new RoleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
            'nestRoles'    => $user->getChildRoleList()
        ]);
    }

    public function actionUpdateNestableRoles(){
        $req = Yii::$app->request;
        $user = new UserService();
        $oldRole = $user->getChildRoleList();

        // Xóa Nest Role cũ
        $this->updateRoles($oldRole, 'delete');
        // Cập nhật Nest Role mới
        $this->updateRoles($req->post('roles'));
    }

    protected function updateRoles($roles, $method = 'update'){

        foreach ($roles as $role){
            $children = isset($role['children']) && !empty($role['children']) ? $role['children'] : [];

            if(!empty($children)) {
                foreach ($children as $child){
                    if($method == 'delete'){
                        AuthItemChild::deleteAll(['parent' => $role['id'], 'child' => $child['id']]);
                    } else {
                        $auth = Yii::$app->authManager;
                        list($rparent, $rchild) = [$auth->getRole($role['id']), $auth->getRole($child['id'])];
                        $auth->addChild($rparent, $rchild);
                    }
                }
                $this->updateRoles($children, $method);
            }
        }

    }






    /**
     * Displays a single Role model.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function actionView($name)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title'   => $name,
                'content' => $this->renderPartial('view', [
                    'model' => $this->findModel($name),
                ]),
                'footer'  => Html::button(Yii::t('rbac', 'Close'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a(Yii::t('rbac', 'Edit'), ['update', 'name' => $name], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
            ];
        } else {
            return $this->render('view', [
                'model' => $this->findModel($name),
            ]);
        }
    }

    /**
     * Creates a new Role model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Role(null);

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title'   => Yii::t('rbac', "Create new {0}", ["Role"]),
                    'content' => $this->renderPartial('create', [
                        'model' => $model,
                    ]),
                    'footer'  => Html::button(Yii::t('rbac', 'Close'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button(Yii::t('rbac', 'Save'), ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
//                \johnitvn\userplus\Helper::dump($model);
                return [
                    'forceReload' => 'true',
                    'title'       => Yii::t('rbac', "Create new {0}", ["Role"]),
                    'content'     => '<span class="text-success">' . Yii::t('rbac', "Have been create new {0} success", ["Role"]) . '</span>',
                    'footer'      => Html::button(Yii::t('rbac', 'Close'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a(Yii::t('rbac', 'Create More'), ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title'   => Yii::t('rbac', "Create new {0}", ["Role"]),
                    'content' => $this->renderPartial('create', [
                        'model' => $model,
                    ]),
                    'footer'  => Html::button(Yii::t('rbac', 'Close'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button(Yii::t('rbac', 'Save'), ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        } else {
            /*
             *   Process for non-ajax request
             */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'name' => $model->name]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing Role model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function actionUpdate($name)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($name);

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title'   => Yii::t('rbac', "Update {0}", ['"' . $name . '" Role']),
                    'content' => $this->renderPartial('update', [
                        'model' => $this->findModel($name),
                    ]),
                    'footer'  => Html::button(Yii::t('rbac', 'Close'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button(Yii::t('rbac', 'Save'), ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {

                return [
                    'forceReload' => 'true',
                    'title'       => $name,
                    'content'     => $this->renderPartial('view', [
                        'model' => $this->findModel($name),
                    ]),
                    'footer'      => Html::button(Yii::t('rbac', 'Close'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a(Yii::t('rbac', 'Edit'), ['update', 'name' => $name], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title'   => Yii::t('rbac', "Update {0}", ['"' . $name . '" Role']),
                    'content' => $this->renderPartial('update', [
                        'model' => $model,
                    ]),
                    'footer'  => Html::button(Yii::t('rbac', 'Close'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button(Yii::t('rbac', 'Save'), ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        } else {
            /*
             *   Process for non-ajax request
             */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'name' => $model->name]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Role model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function actionDelete($name)
    {
        $request = Yii::$app->request;
        $this->findModel($name)->delete();

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => true];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the Role model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $name
     *
     * @return Role the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($name)
    {
        if (($model = Role::find($name)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('rbac', 'The requested page does not exist.'));
        }
    }

}
