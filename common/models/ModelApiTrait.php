<?php
namespace common\models;


trait ModelApiTrait
{
    public $geometry;
    public $distance;

    public function fields()
    {
        $parent = parent::fields();
        unset($parent['geom']);
        return $parent;
    }

    public function extraFields()
    {
        return [
            'geometry' => function ($item) {
                if($item->geom) return [
                    'type'        => $item->geometryType,
                    'coordinates' => $item->geom,
                ];

                return null;
            },
            'distance' => function ($item) {
                return round($item->distance);
            },
        ];
    }

    public function getDistanceText(){
        if($this->distance > 1000){
            return number_format($this->distance/1000, 2)." km";
        } else {
            return number_format($this->distance)." m";
        }
    }
}