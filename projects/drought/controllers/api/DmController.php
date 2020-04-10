<?php
namespace drought\controllers\api;

use common\controllers\ApiController;
use common\models\DmPhuong;
use drought\models\Gallery;
use drought\models\HcTinh;
use drought\models\HcVung;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;

class DmController extends ApiController
{
    public function beforeAction($action)
    {
        if(in_array($action->id, ['quan'])){
            $this->attachBehaviors([
                'authenticator' => [
                    'class' => CompositeAuth::className(),
                    'authMethods' => [
                        HttpBasicAuth::className(),
                        HttpBearerAuth::className(),
                        QueryParamAuth::className(),
                    ],
                ]
            ]);
        }

        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }


    public function actionTinh()
    {
        $value = app('request')->post('value');
        $mavung = data_get(app('request')->post('depdrop_parents'), '0');

        $list = collect(data_get(HcVung::find()->with('tinhs')->where(['mavung' => $mavung])->one(), 'tinhs'))->map(function ($item){
            return ['id' => $item->gid, 'name' => $item->tentinh];
        });


        return [
            'output' => $list, 'selected'=> $value
        ];
    }

    public function actionVung()
    {
        $query = HcVung::find()->select('mavung, tenvung');
        return $query->pluck('tenvung', 'mavung');
    }

    public function actionResults()
    {
        $data = Gallery::find()->select('code, id')->andWhere(['type' => 2])->asArray()->all();
        $data = collect($data)->map(function ($i){
            $layers = (string)Str::of($i['code'])->slug('_')->prepend('drought:m_v_');
            return array_merge($i, [
                'layers' => $layers,
            ]);
        })->all();

        $vung = collect(HcVung::find()->with('tinhs')->select('id, mavung, tenvung')->all())->map(function ($i){
            $cql = Str::of(implode(',', Arr::pluck($i->tinhs, 'gid')))->prepend('gid in (')->append(')');
            return [
                'value' => $i->id,
                'label' => $i->tenvung,
                'cql' => (string)$cql
            ];
        });

        return [
            'data' => [
                'vungs' => $vung->all(),
                'results' => $this->parseCat($data, 'id', 'code', ['layers'])
            ]
        ];
    }


    public function parseCat($data, $value, $label, $only = []){
        return collect($data)->map(function ($i, $k) use($value, $label, $only){
            $data = collect($i);

            if($only) $data = $data->only($only);

            return $data->merge([
                'label' => $i[$label],
                'value' => $i[$value],
            ])->all();
        })->values()->all();
    }
}