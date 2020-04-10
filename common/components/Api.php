<?php
namespace common\components;

//use GuzzleHttp\Client;
use yii\base\Component;
//use yii\httpclient\Client;
//use yii\httpclient\Client;
//use yii\httpclient\Client;
use yii\web\Cookie;
use rob006\simpleauth\YiiAuthenticator as Authenticator;

class Api extends Component
{
    public $dataMap = [];

    public function get($route){

        $fixtures = collect($this->dataMap)->map(function($item){ return require $item;})->collapse();

        $data = $fixtures->get($route, []);

        if(empty($data)){
            $username = optional(user()->identity)->username;
            $method = request()->isGet ? 'GET' : 'POST';
            try {
                $client = new \GuzzleHttp\Client([
                    'base_uri' => url('/api', true).'/',
                    'curl' => [CURLOPT_SSL_VERIFYPEER => false],
                    'verify' => false
                ]);

                $route = implode('/', array_filter(explode('/', $route)));
                $url = parse_url($route);
                $query = [];
                parse_str(data_get($url, 'query'), $query);

                $response = $client->request($method, $url['path'], [
                    'query' => array_merge($query, ['access-token' => $username])
                ]);

                $data = json_decode((string)$response->getBody(), true);

                return is_null($data) ? (string)$response->getBody() : $data;
            } catch (\Exception $e){
                dd($e);
                return [];
            }

//            $client = new \yii\httpclient\Client([
//                'baseUrl' => url('/api', true),
//            ]);
//            $cookies = \Yii::$app->request->cookies;
//            dd(user()->identity);
//            try {
//                $request = $client->createRequest()
//                    ->setMethod('GET')
//                    ->setFormat(\yii\httpclient\Client::FORMAT_JSON)
////                    ->setData([
////                        'access-token' => 'quan8'
////                    ])
////                    ->setContent('{access-token: "quan8"}')
//                    ->setUrl($route);
//                $request = Authenticator::authenticate($request, Authenticator::METHOD_HEADER, 'ttungbmt');
//                $response = $request->send();
////                dd($request, $response);
//
//                $data = $response->getData();
//
//                return $data;
//            } catch (\Exception $e){
//                dump("Not Found data: /api/{$route}");
//                return [];
//            }
        }

        return $data;
    }
}