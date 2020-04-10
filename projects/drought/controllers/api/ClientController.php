<?php
namespace raumau\controllers\api;

use common\controllers\MyApiController;
use yii\httpclient\Client;

class ClientController extends MyApiController
{
    public function actionIndex(){
        $request = \Yii::$app->request;
        $method = $request->method;
        $params = collect(request()->all());
        $url = $params->pull('url');

        $client = new Client();
        $response = $client->createRequest()
            ->setMethod($method)
            ->setUrl($url)
            ->setData($params->all())
            ->send();

        return $response->getData();
    }
}