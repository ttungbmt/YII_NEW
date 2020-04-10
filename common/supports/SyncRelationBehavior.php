<?php
namespace common\supports;

use common\models\MyModel;
use yii\base\DynamicModel;
use yii\base\Model;
use yii\db\Query;

class SyncRelationBehavior extends \frostealth\yii2\behaviors\SyncRelationBehavior
{
    public function syncOne($name, $data, $delete = true){
        $data = collect($data);
        $ids = collect([]);
        $added = [];
        $addedItems = [];
        $updated = [];
        $deleted = [];

        $relation = $this->owner->getRelation($name);
        $relationClass = $relation->modelClass;
        $foreignKey = head(array_keys($relation->link));
        $primaryKey = head($relation->link);
        $command = \Yii::$app->db->createCommand();
        // ADDED
        $columns = collect((new $relationClass)->attributes())->filter(function($i){
            return $i !== 'id';
        })->all();


        foreach ($data as $k => $attrs){
            $id = isset($attrs['id']) ? (int)$attrs['id'] : $k;
            if(isset($attrs['id'])){
                $ids->push($id);
            } else {
                $added[] = $k;
            }
        }


        $current = collect($this->getLinkedIds($name));
        $deleted = $current->diff($ids)->all();
        $updated = collect($ids)->whereNotIn('id', $deleted);

        // UPDATED
        $relations = $relation->indexBy(head($relationClass::primaryKey()))->all();
        $updatedItems = $data->whereIn('id', $updated);

        // ADDED
        foreach ($added as $i){
            $model = new DynamicModel($columns);
            $v = data_get($data->all(), $i);
            if($v instanceof MyModel){
                $v = $v->attributes;
            }

            $model->setAttributes($v, false);
            $model->{$foreignKey} = $this->owner->{$primaryKey};
            $addedItems[] = $model->toArray();
        }



        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        $tableName = $relationClass::tableName();

        try {
            foreach ($updatedItems as $k => $attrs){
                $attrs = $attrs instanceof Model ? $attrs->attributes : $attrs;
                $id = $attrs['id'];
                $model = data_get($relations, $id);
                $model->setAttributes($attrs, false);
                $model->save();
            }

            $command->batchInsert($tableName, $columns, $addedItems)->execute();

            $command->delete($tableName, ['id' => $deleted])->execute();
            $transaction->commit();
        } catch(\Exception $e) {
            $transaction->rollback();

        }
    }

    public function sync($name, array $ids, $delete = true)
    {
        $records = $this->formatRecordsList($ids);
        $ids = array_keys($records);

        /** @var \yii\db\ActiveRecordInterface $relationClass */
        $relation = $this->owner->getRelation($name);
        $relationClass = $relation->modelClass;

        $current = $this->getLinkedIds($name);
        $unlink = array_diff($current, $ids);
        $link = array_diff($ids, $current);

        $models = !empty($unlink) ? $relationClass::findAll($unlink) : [];

        foreach ($models as $model) {
            $this->owner->unlink($name, $model, $delete);
        }

        $models = !empty($link) ? $relationClass::findAll($link) : [];

        foreach ($models as $model) {
            $extraColumns = data_get($records, $model->getId(), []);

            $this->owner->link($name, $model, $extraColumns);
        }

        $relations = $relation->indexBy(head($relationClass::primaryKey()))->all();

        if($relation->via){
            $table = head($relation->via->from);
            $via = $relation->via->indexBy(head($relation->link))->all();

            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
                foreach ($via as $i => $item){
                    $model = new DynamicModel($item);
                    $v = data_get($records, $i);
                    $model->setAttributes($v, false);
                    if($model->toArray() != $item && $model->id){

                        $connection->createCommand()->update($table, $model->toArray(), "id = {$model->id}")->execute();
                    }
                }
                $transaction->commit();
            } catch(\Exception $e) {
                $transaction->rollback();
            }
        }

        $this->afterSync($name);

        return ['linked' => $link, 'unlinked' => $unlink];
    }

    /**
     * Format the sync / toggle record list so that it is keyed by ID.
     *
     * @param  array  $records
     * @return array
     */
    protected function formatRecordsList(array $records)
    {
        return collect($records)->mapWithKeys(function ($attributes, $id) {
            if (! is_array($attributes)) {
                list($id, $attributes) = [$attributes, []];
            }


            if(isset($attributes['id'])){
                $id = empty($attributes['id']) ? uniqid('new-') : $attributes['id'];
            }

            return [$id => $attributes];
        })->all();
    }

}