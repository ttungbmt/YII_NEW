<?php
namespace common\models;

use yii\base\DynamicModel;

class DynamicImportForm extends DynamicModel
{
    protected function arr_search($dm, $field){
        $v = collect($dm)->search($field);
        return $v === false ? null : $v;
    }

    protected function arr_where($dm, $field){
        if($field === 'maphuong' || $field === 'px'){
            $v = collect($dm)
                ->where('tenquan', isset($this->maquan) ? $this->maquan : $this->qh)
                ->where('tenphuong', $this->{$field})
                ->first();
            ;
            $v = collect($v)->get('maphuong');
        } else {
            $v = collect($dm)->firstWhere('tenquan', $this->{$field});
            $v = collect($v)->get('maquan');
        }

        return $v === false ? null : $v;
    }

    protected function filterChitieu($inp){
        if(is_string($inp)){
            $v = trim($inp);
            if(strpos($v, ',')) {
                $arr = collect(explode(',', $v))->map(function ($i){return trim($i);});
                return $arr->count() <= 2 ? $arr->implode('.') : $arr->implode('');
            };
            if($v == "" or $v == "-") return null;
        }
        return $inp;
    }

    public function formAttrs()
    {
        $attribs = [];
        foreach($this->attributes as $k => $value) {
            $attribs +=  [$k => ['label' => ''.$k]];
        }

        return $attribs;
    }
}