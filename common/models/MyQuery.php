<?php
namespace common\models;

use common\supports\NestedSetTrait;
use creocoder\nestedsets\NestedSetsQueryBehavior;
use nanson\postgis\db\PostgisQueryTrait;
use paulzi\nestedsets\NestedSetsQueryTrait;
use yii\db\ActiveQuery;
use yii\db\Expression;

class MyQuery extends ActiveQuery
{
    use PostgisQueryTrait, NestedSetTrait, MyQueryTrait;
    public $polymorphic;
    public $as;

    public function pluck($value, $key = null)
    {
        return collect($this->all())->pluck($value, $key);
    }

    public function map($cb)
    {
        return collect($this->all())->map($cb);
    }

    public function selectBounds(){
        return $this->addSelect('ST_Extent (geom) AS bounds');
    }

    public function whereIn($ids){
        $model = new $this->modelClass;
        return $this->andWhere(['in', $model->firstPrimaryKey(), $ids]);
    }

    public function whereId($id){
        $model = new $this->modelClass;
        return $this->andWhere([$model->firstPrimaryKey() => $id]);
    }

    public function whereIntersect($geoJSON){
        if(!$geoJSON) return null;

        if($geoJSON && strtolower($geoJSON['type']) === 'circle'){
            $orderType = request('orderType', 'ASC');

            $distance = $geoJSON['radius'] ?? data_get($geoJSON, 'distance');
            $geoJSON = ['type' => 'Point', 'coordinates' => $geoJSON['coordinates']];
            $geom = new Expression("ST_Buffer(ST_GeomFromGeoJSON('".json_encode($geoJSON)."')::geography, {$distance})");
            $pt_geom = new Expression("ST_GeomFromGeoJSON('".json_encode($geoJSON)."')::geography");
            $this->orderBy("ST_Distance(geom::geography, {$pt_geom}) {$orderType}");
            $this->addSelect("ST_Distance(geom::geography, {$pt_geom}) distance");
        } else {
            $geom = new Expression("ST_SetSRID(ST_GeomFromGeoJSON('".json_encode($geoJSON)."'), 4326)");
        }


        return $this->andWhere("ST_Intersects(geom, {$geom})");
    }

    protected function getAlias(){
        $table = empty($this->from) ? [] : array_slice($this->from, 0, 1);
        $alias = !$table || !isAssoc($table) ? $this->getTableName() : head(array_keys($table));
        return $alias;
    }

    public function joinPhuong(){
        return $this
            ->addSelect(['p.tenphuong', 'p.tenquan'])
            ->leftJoin(['p' => 'dm_phuong'], "p.maphuong = {$this->getAlias()}.maphuong");
    }


    public function filterPQ(){
//        $user = auth()->current();
//
//        $roles = $user ? collect($user->roles) : null;
//        $info = $user->info;
//
//        $model = with(new $this->modelClass);
//        $fields = collect($model->attributes)->keys();
//        $prefix = $model->tableName().'.';
//
//        if($fields->contains('user_id')){
//            $this->joinInfo();
//            $prefix = 'user_info.';
//        } elseif ($fields->contains('maquan') && method_exists($model, 'getQuan')){
//            $this->joinWith('quan');
//
//        } elseif ($fields->contains('maphuong') && method_exists($model, 'getPhuong')){
//            $this->joinWith('phuong');
//        }
//
//        if($roles->has(PQConst::QUAN)){
//            $this->andWhere([$prefix.'maquan' => $info->maquan]);
//        } elseif ($roles->has(PQConst::PHUONG)){
//            $this->andWhere([$prefix.'maquan' => $info->maquan, $prefix.'maphuong' => $info->maphuong]);
//        }

        return $this;
    }
}