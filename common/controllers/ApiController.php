<?php
namespace common\controllers;

use common\models\DmPhuong;
use common\models\DmQuan;
use Yii;
use yii\filters\ContentNegotiator;
use yii\rest\Controller;
use yii\web\Response;

class ApiController extends Controller
{
    protected $data;

    public $request;

    public $response;

    public function init()
    {
        parent::init();
        if ($this->request === null) {
            $this->request = Yii::$app->getRequest();
        }
        if ($this->response === null) {
            $this->response = Yii::$app->getResponse();
        }
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'contentNegotiator' => [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ]);
    }

    public function actionPhuong(){
        $value = app('request')->post('value');
        $maquan = data_get(app('request')->post('depdrop_parents'), '0');
        $list_phuong = DmPhuong::find()->where(['maquan' => $maquan])->map(function ($item){
            return ['id' => $item->maphuong, 'name' => $item->tenphuong];
        });

        return [
            'output' => $list_phuong, 'selected'=> $value
        ];
    }

    public function actionQuan(){
        return DmQuan::find()->pluck('ten_quan', 'mahc');
    }

}