<?php
namespace common\controllers;
use yii\db\Expression;
use yii\db\Query;

class MapApiController extends MyApiController
{
    public function actionBounds(){
        $layers = request('layers');
        if($layers){
            $table = last(explode(':', $layers));
            $data = (new Query())->select(new Expression('ST_AsGeoJSON(ST_SetSRID(ST_Extent(geom), 4326)) geojson'))->from($table)->one();
            $geojson = json_decode(head($data), true);
            return $geojson;
        }
    }

    public function actionChoropleth(){
        $layer_geo = request('layer_geo');
        $table = data_get(api('layer_meta'), "{$layer_geo}.table");
        $form = collect(request('form'));

        $f_code = 'maquan';
        $f_text = 'ten_quan';
        $dm_tb = 'dm_quan';

        if($maquan = $form->get('maquan')){
            $f_code = 'maphuong';
            $f_text = 'tenphuong';
            $dm_tb = 'dm_phuong';
        }

        $subQuery = (new Query())->from($table)->select([$f_code, 'count' => 'COUNT(*)'])->groupBy($f_code)->andFilterWhere(['maquan' => $maquan]);
        $query = (new Query())->from(['dm' => $dm_tb])->select([
            'label' => "dm.{$f_text}",
            'count' => 'COALESCE(tb.count, 0)',
            'geometry' => 'ST_AsGeoJSON(dm.geom)'
        ])
            ->leftJoin(['tb' => $subQuery], "tb.{$f_code} = dm.{$f_code}")
            ->orderBy($f_text)
            ->andFilterWhere(['maquan' => $maquan])
        ;

//        dd($subQuery->createCommand()->getRawSql(), $query->createCommand()->getRawSql());
        $data = $query->all();

        return [
            'items' => toFeature($data),
            'html' => $this->renderPartial('@common_theme/admin/api/choropleth/index', ['data' => $data, 'isQuan' => $maquan])
        ];
    }
}