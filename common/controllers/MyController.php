<?php
namespace common\controllers;

use Carbon\Carbon;
use common\supports\ModelNotFoundException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class MyController extends Controller
{
    use RestTrait;

    public function init()
    {
        parent::init();
    }

    protected $data = [];

    protected function findModel($id)
    {
        if (($model = ($this->modelClass)::findOne($id)) !== null) {
            return $model;
        } else {
            throw new ModelNotFoundException('The model does not exist.');
        }
    }

    public function render($view, $params = [])
    {
        $params['api'] = app('api');
        return parent::render($view, $params);
    }


    public function renderJson($data){
        return $this->asJson($data);
    }
}