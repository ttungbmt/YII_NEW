<?php

namespace drought\controllers;

use Carbon\Carbon;
use covid\models\Cabenh;
use covid\models\HcPhuong;
use covid\models\HcQuan;
use covid\models\search\CabenhSearch;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Yii;
use yii\helpers\Url;
use yii\httpclient\Client;



class MapsController extends AppController {
    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public $layout = '@common/themes/admin/layouts/blank';

    public function actionIndex() {


        return $this->render('index');
    }

    public function actionConfigs() {
        $user = \Yii::$app->user;
        $data = [];

        $data['map'] = [
            'popup' => ['cabenh'],

        ];

        if (!$user->isGuest) {
            $identity = $user->identity;
            $info = $identity->info ? $identity->info->toArray() : [];
            $data['auth'] = [
                'user'        => collect([
                    'id'       => $identity->id,
                    'username' => $identity->username,
                ])->merge(collect($info)->only(['fullname', 'email'])),
                'permissions' => Arr::pluck(auth()->getPermissionsByUser($identity->id), 'name'),
                'roles'       => Arr::pluck(auth()->getRolesByUser($identity->id), 'name'),
            ];

            $data['dm']['dm_quan'] = HcQuan::find()->orderBy('order')->pluck('tenquan', 'maquan')->all();
        }

        $data['map']['layer_tree'] = $this->getLayerTree($data['map']);


        $data['app'] = [
            'app_name'    => params('app_name'),
            'app_logo'    => params('assets.logo'),
            'description' => params('app_name'),

            'headerMenu' => [
                ['url' => '/maps', 'text' => 'Trang chủ'],
                ['url' => '/maps/cabenh', 'text' => 'Ca bệnh'],
                ['url' => '/maps/vung', 'text' => 'Vùng'],
            ],
        ];


        return $this->asJson($data);
    }

    public function getLayerTree($data) {
//        $_3mAgo = 'ngaymacbenh_nv >= '.(Carbon::now())->subMonths(3)->format('Y-m-d');

        return [
            [
                "title"   => "Quận huyện",
                "active"  => role('quan'),
                "type"    => 'wms',
                "key"     => "hc_quan",
                "options" => [
                    "url"    => "/geoserver/ows?",
                    "layers" => "dichte:hc_quan",
                    "zIndex" => 1
                ],
            ],
            [
                "title"   => "Phường xã",
                "type"    => 'wms',
                "key"     => "hc_phuong",
                "active"  => role('phuong'),
                "options" => [
                    "url"    => "/geoserver/ows?",
                    "layers" => "dichte:hc_phuong",
                    "zIndex" => 2
                ],
            ],
            [
                "title"   => "Ranh tổ",
                "type"    => 'wms',
                "key"     => "ranhto",
                "options" => [
                    "url"    => "/geoserver/ows?",
                    "layers" => "dichte:ranh_to",
                    "zIndex" => 3
                ],
            ],
            [
                "title"   => "Ca bệnh",
                "type"    => 'wms',
                "key"     => "cabenh",
                'active'  => true,
                "options" => [
                    "url"    => "/geoportal/ows?",
                    "tiled"  => true,
                    "layers" => "covid:cabenh",
                    "zIndex" => 10
                ],
            ],
            [
                "title"   => "Vùng dịch",
                "type"    => 'wms',
                "key"     => "vung_dich",
                'active'  => false,
                "options" => [
                    "url"    => "/geoportal/ows?",
                    "layers" => "covid:vung_dich",
                    "zIndex" => 10
                ],
            ],
            [
                "title"   => "Vùng cách ly",
                "type"    => 'wms',
                "key"     => "vung_cl",
                'active'  => false,
                "options" => [
                    "url"    => "/geoportal/ows?",
                    "layers" => "covid:vung_cl",
                    "zIndex" => 10
                ],
            ],
            [
                "title"   => "Trung tâm thương mại",
                "type"    => 'wms',
                "key"     => "tttm",
                "options" => [
                    "url"    => "/geoportal/ows?",
                    "layers" => "covid:tttm",
                    "zIndex" => 8
                ],
            ],
            [
                "title"   => "Cơ sở y tế",
                "type"    => 'wms',
                "key"     => "cs_yte",
                "options" => [
                    "url"    => "/geoportal/ows?",
                    "layers" => "covid:cs_yte",
                    "zIndex" => 8
                ],
            ],
            [
                "title"   => "Cơ sở giáo dục",
                "type"    => 'wms',
                "key"     => "cs_giaoduc",
                "options" => [
                    "url"    => "/geoportal/ows?",
                    "layers" => "covid:cs_giaoduc",
                    "zIndex" => 8
                ],
            ],
        ];
    }


    public function actionCabenh() {
        $searchModel = new CabenhSearch();
        return $this->getDs($searchModel);
    }

    protected function getDs($searchModel) {
        # https://github.com/jlorente/yii2-datatables/blob/master/src/models/SearchModel.php
        $params = request()->queryParams;
        if (request()->has('export_type')) {
            $params = ['form' => json_decode(request('formData'), true)];
        }

        $dataProvider = $searchModel->search($params);

        if (request()->has('export_type')) {
            return $searchModel->exportData($dataProvider);
        }

        if (request()->has('columns') && !request()->has('draw')) {
            return $this->asJson($searchModel->getConfigs($dataProvider));
        }

        return $this->asJson($searchModel->getDraw($dataProvider));
    }


    public function renderInfo($name, $data) {
        $resp = [];
        $data = opt($data);

        switch ($name) {
            case 'cabenh':
                $model = Cabenh::getPopupInfo($data->id);
                $resp['model'] = $model;
                break;
        }

        return $resp;
    }

    public function actionGetWmsInfo() {
        $client = new Client();
        $postData = request()->all();
        $respData = [];

        foreach (data_get($postData, 'items', []) as $k => $i) {
            $feature = opt($i);
            $url = data_get($feature, 'infoUrl');
            $req = $client->get(Url::to($url, true));

            try {
                $resp = $req->send()->getData();
                $info = data_get($resp, 'features.0');

                if ($info) {
                    $layerName = $feature->layers;
                    $data = array_merge(['iid' => last(explode('.', $info['id']))], data_get($resp, 'features.0.properties'));

                    return $this->asJson([
                        'status' => 'OK',
                        'data'   => array_merge([
                            'uuid'   => $feature->uuid,
                            'layers' => $layerName,
                        ], $this->renderInfo($feature->key, $data))
                    ]);
                }
            } catch (\Exception $e) {
                return $this->asJson([
                    'status'  => 'FAILED',
                    'message' => $e->getMessage(),
                ]);
            }
        }

        return $this->asJson([
            'status' => 'OK',
            'data'   => $respData
        ]);
    }
}