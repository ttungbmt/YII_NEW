<?php
namespace common\models;

use Illuminate\Support\Str;
use yii\db\ActiveRecord;

trait HasRelationships
{
    protected function getMorphs($name, $type, $id)
    {
        return [$type ?: $name.'_type', $id ?: $name.'_id'];
    }

    public function morphMany($related, $name = null, $type = null, $id = null, $localKey = null){
        list($type, $id) = $this->getMorphs($name, $type, $id);
        $localKey = $localKey ?: $this->firstPrimaryKey();

        return $this->hasMany($related, [$id => $localKey])->andWhere([$type => self::className()]);
    }

    public function morphTo($name = null, $type = null, $id = null, $ownerKey = 'id')
    {
        list($type, $id) = $this->getMorphs(
            Str::snake($name), $type, $id
        );
        $class = $this->{$type};
        $instance = new $class;

        if (!$class) {
            $class = ActiveRecord::className();
        } else {
            $ownerKey= $instance->firstPrimaryKey();
        }

        $query = $this->createRelationQuery($class, [$ownerKey => $id], false);
        return $query;
    }
}