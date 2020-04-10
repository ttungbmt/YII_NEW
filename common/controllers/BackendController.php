<?php

namespace common\controllers;

use common\components\Access;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\UnauthorizedHttpException;

class BackendController extends MyController
{
    public $layout = '@app/views/layouts/master';
    protected $accessHook = [
    ];

    public function init() {
        parent::init();
        $this->accessHook = new Access();
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'matchCallback' => function($rule, $action){
                            if(method_exists($this, 'access') && $access = $this->access()){

                                foreach ($access as $auth => $item){
                                    $user = \Yii::$app->user;
                                    $cond = (is_numeric($auth) ? false : (in_array($action->id, $item)) && !$user->can($auth));
                                    throw_if($cond, new UnauthorizedHttpException('Bạn không đủ quyền truy cập'));
                                }
                            }

                            return true;
                        }
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
}
