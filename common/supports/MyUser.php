<?php
namespace common\supports;

use Illuminate\Support\Collection;

class MyUser extends \yii\web\User
{
    public $identityClass = 'common\models\User';

    public $loginUrl = ['login'];

    public function is($slug, $operator = null, $strict = true)
    {
        $operator = is_null($operator) ? $this->parseOperator($slug) : $operator;
        $slug = $this->hasDelimiterToArray($slug);

        if (is_array($slug) ) {
            if ( ! in_array($operator, ['and', 'or']) ) {
                $e = 'Invalid operator, available operators are "and", "or".';
                throw new \InvalidArgumentException($e);
            }
            $call = 'isWith' . ucwords($operator);
            return $this->$call($slug);
        }

        $roles = getRoles();
        if(empty($roles)) return false;

        return $strict ? in_array($slug, $roles) : $this->can($slug);
    }

    public function hasRoles($slug, $operator = null, $strict = false){
        return $this->is($slug, $operator, $strict);
    }

    public function hasPerms($slug = null, array $params = []){
//        $slug = $slug ?: $this->getRoute();

        if(!session('permissions')){
            session(['permissions' => array_keys(auth()->getPermissionsByUser($this->getId()))]);
        }
        $perms = session('permissions');

        if(empty($perms)) return false;

        $action = $this->mapArray($slug, function ($item) use ($perms, $params){
            foreach ($perms as $perm){
                if(str_is($perm, $item)) {
                    if(!empty($params)) return $this->can($perm, $params);

                    return true;
                }
            }
        });

        return $this->withArrEmpty($action);
    }

    protected function hasDelimiterToArray($str)
    {
        if ( is_string($str) && preg_match('/[,|]/is', $str) ) {
            return preg_split('/ ?[,|] ?/', strtolower($str));
        }
        return (is_array($str) ?
            array_filter($str, 'strtolower') : is_object($str)) ?
                $str : strtolower($str);
    }

    protected function parseOperator($str)
    {
        if ( is_array($str) ) { $str = implode(',', $str); }

        if ( preg_match('/([,|])(?:\s+)?/', $str, $m) ) {
            return $m[1] == '|' ? 'or' : 'and';
        }

        return false;
    }

    protected function isWithAnd($slug)
    {
        foreach ($slug as $check) {
            if ( ! $this->is($check) ) {
                return false;
            }
        }
        return true;
    }

    public function isWithOr($slug)
    {
        foreach ($slug as $check) {
            if ( $this->is($check) ) return true;
        }
        return false;
    }

    protected function withArrEmpty($arr) {
        if(is_array($arr)){
            if(empty(array_filter($arr))){
                return false;
            }
            return true;
        }

        return is_null($arr) ? false : $arr;
    }

    protected function mapArray($item, \Closure $closure)
    {
        $item = $this->hasDelimiterToArray($item);
        // item is a collection.
        if ($item instanceof Collection) {
            $item = $this->collectionAsArray(
                $item->lists('name')
            );
        }

        // multiple items
        if ( is_array($item) ) {

            // is an array of One Role/Permission
            // its an array containing id
            // we dont have to loop through
            if ( isset($item['id']) )
                return $closure((int) $item['id']);
            // is an array of slugs
            return array_map($closure, $item);
        }
        // single item
        return $closure($item);
    }
}