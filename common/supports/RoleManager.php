<?php
namespace common\supports;

use pcd\constants\RoleConst;

class RoleManager
{
    protected $identity;

    public function __construct()
    {
        $this->identity = \Yii::$app->user->identity;
    }

    public static function init(){
        if(role(RoleConst::QUAN)){
            return new QuanRole;
        } elseif (role(RoleConst::PHUONG)){
            return new PhuongRole;
        }

        return new self;
    }

    public function docGuideUrl(){
        return asset('/storage/docs/HDSD DICH TE_TP.pdf');
    }

    public function filterCabenh(&$model, $fields = []){
        return $model;
    }

    public function getMaHc(){
        return null;
    }
}

class QuanRole extends RoleManager
{
    public function docGuideUrl(){
        return asset('/storage/docs/HDSD DICH TE_PHUONG.pdf');
    }

    public function filterCabenh(&$model, $fields = ['maquan' => 'ma_quan']){
        $maquan = data_get($this->identity, 'info.ma_quan');
        return $model->andWhere([$fields['maquan'] => $maquan]);
    }

    public function getMaHc($field = 'maquan'){
        return data_get($this->identity, 'info.ma_quan');
    }
}

class PhuongRole extends RoleManager
{
    public function docGuideUrl(){
        return asset('/storage/docs/HDSD DICH TE_QUAN.pdf');
    }

    public function filterCabenh(&$model, $fields = ['maquan' => 'ma_quan', 'maphuong' => 'ma_phuong']){
        $maquan = data_get($this->identity, 'info.ma_quan');
        $maphuong = data_get($this->identity, 'info.ma_phuong');
        return $model->andWhere([$fields['maquan'] => $maquan, $fields['maphuong'] => $maphuong]);
    }

    public function getMaHc($field = 'maphuong'){
        return data_get($this->identity, 'info.ma_phuong');
    }
}