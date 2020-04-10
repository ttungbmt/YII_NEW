<?php
namespace common\modules\auth\supports;

use app\modules\auth\models\VUser;
use Yii;
use yii\db\Query;
use yii\rbac\Item;

class UserService
{
    public $user;
    public $authUser;
    protected $db;
    public $authManager;

    public function __construct()
    {
        $this->authManager = Yii::$app->authManager;
        $this->user = Yii::$app->user;
        $this->authUser = $this->user->identity;
        $class = $this->user->identityClass;
        $this->db = new $class();

    }

    public function getChildren($parent){
        return collect($this->authManager->getChildren($parent));
    }

    public function getChildRole($parentName){
        return $this->getChildren($parentName)->filter(function($value, $key){
            return $value->type == Item::TYPE_ROLE;
        })->all();
    }

    public function getChildPermission($parentName){
        return $this->getChildren($parentName)->filter(function($value, $key){
            return $value->type == Item::TYPE_PERMISSION;
        })->all();
    }

    public function removeChild($parent, $child){
        $auth = $this->authManager;
        $conditions = [
            'parent' => $parent->name,
            'child' => is_array($child) ? $child : $child->name
        ];

        $result = $auth->db->createCommand()
                ->delete($auth->itemChildTable, $conditions)
                ->execute() > 0;

        $auth->invalidateCache();

        return $result;
    }

    public function getChildRoleList(){
        $type = Item::TYPE_ROLE;
        $query = (new Query)
            ->select(['parent', 'child'])
            ->from($this->authManager->itemChildTable)
            ->leftJoin('auth_item as parent', 'parent.name = auth_item_child.parent')
            ->leftJoin('auth_item as child', 'child.name = auth_item_child.child')
            ->where(['parent.type' => $type, 'child.type' => $type]);

        $master = Yii::$app->db->createCommand("
                SELECT name FROM auth_item WHERE auth_item.type = {$type} EXCEPT 
                SELECT child FROM auth_item_child LEFT JOIN auth_item ON auth_item. NAME = auth_item_child.child WHERE auth_item.type = {$type}")
            ->queryAll();
        $master = collect($master)->mapWithKeys(function ($item){return [$item['name'] => null];})->all();

        $nestRoles = collect($query->all())->pluck('parent', 'child')
            ->merge($master)
            ->all();
        return $this->parseTree($nestRoles);
    }

    /*
     * http://stackoverflow.com/questions/2915748/convert-a-series-of-parent-child-relationships-into-a-hierarchical-tree
     */

    public function parseTree($tree, $root = null) {
        $return = [];
//        dump($tree);
        # Traverse the tree and search for direct children of the root
        foreach($tree as $child => $parent) {
            # A direct child is found
            if($parent == $root) {
//                dump($parent, $root);
                # Remove item from tree (we don't need to traverse this again)
                unset($tree[$child]);
                # Append the child into result array and parse its children
                $return[] = array_merge([
                    'id' => $child,
                ], !empty($this->parseTree($tree, $child)) ? ['children' => $this->parseTree($tree, $child)] : []);
            }
        }
        return empty($return) ? null : $return;
    }


    public function getId(){
        return $this->user->getId();
    }

    public function getUserName(){
        $identity = $this->user->identity;
        return $identity ? $identity->username : null;
    }

    public function current($userId = null){
        if ($this->user->isGuest) return null;
        $userId = $userId ?: $this->authUser->id;
//        if($userId) return $this->authUser->findOne($userId);
        return (object)$this->authUser->current();
    }

    public function getAll(){
        return $this->db->find()->all();
    }

    public function getRole($name = null){
        return $this->authManager->getRole($name);
    }

    public function getRoles(){
        return $this->authManager->getRoles();
    }

    public function getRolesByUser($userId = null){
        return $this->authManager->getRolesByUser($userId ?: $this->getId());
    }

    public function getPermission($name = null){
        if(!$name) return $this->getPermissionsByUser();

        return $this->authManager->getPermission($name);
    }

    public function getPermissionsByUser($userId = null){
        return $this->authManager->getPermissionsByUser($userId ?: $this->getId());
    }

    public function getPermissionsByRole($roleName = null){
        $auth = $this->authManager;
        if($roleName) return $auth->getPermissionsByRole($roleName);

        $roles = $auth->getRolesByUser($this->getId());
        $perms = [];
        foreach ($roles as $role){
            $permission = $auth->getPermissionsByRole($role->name);
            empty($permission) || $perms = array_merge($perms, $permission);
        }
        return $perms;
    }

    public function isGuest(){
        return $this->user->isGuest;
    }

    public function is($slug, $operator = null){
        $operator = is_null($operator) ? $this->parseOperator($slug) : $operator;
        $slug = $this->hasDelimiterToArray($slug);

        if ( is_array($slug) ) {
            if ( ! in_array($operator, ['and', 'or']) ) {
                $e = 'Invalid operator, available operators are "and", "or".';
                throw new \InvalidArgumentException($e);
            }
            $call = 'isWith' . ucwords($operator);
            return $this->$call($slug);
        }

        $check = $this->getRole($slug);
        return $check ? $this->user->can($slug) : false;
    }

    public function can($slug = null, array $params = []){
        $slug = $slug ?: $this->getRoute();
        $perms = array_keys($this->getPermissionsByUser());

        $action = $this->mapArray($slug, function ($item) use($perms, $params){
            foreach ($perms as $perm){
                if(str_is($perm, $item)) {
                    if(!empty($params)) return $this->user->can($perm, $params);

                    return true;
                }
            }
        });

        return $this->withArrEmpty($action);
    }

    public function hasAccess($item){
        switch ($item->type){
            case Item::TYPE_ROLE:
                if($this->is($item->name)) return true;
                break;
            case Item::TYPE_PERMISSION:
                if($this->can($item->name)) return true;
                break;
        }

        return false;
    }


    public function createRole($item){
        $auth = $this->authManager;
        $name = is_string($item) ? $item : $item['name'];
        if($this->getRole($name)) return false;

        $role = $this->authManager->createRole($name);
        !is_array($item) || $this->fill($role, $item);
        return $this->authManager->add($role);
    }

    public function removeRole($name){
        $role = $this->getRole($name);
        return $role ? $this->authManager->remove($role) : false;
    }

    public function getRoute(){
        $core = Yii::$app->controller;
        $module = $core->module->id;
        $action = $core->action->id;
        $controller = $core->id;
        return str_replace('/', '.', ($module == 'basic' ? "" : "$module.")."$controller.$action");
    }

    /**
     * Revokes all roles from the user.
     *
     * @return bool
     */

    public function removeAllRoles(){
        return $this->authManager->removeAllRoles();
    }

    /**
     * Assigns the given role to the user.
     *
     * @param $role
     *
     * @return bool
     * @internal param $item
     *
     * @internal param \app\modules\auth\services\collection|array|int|object|string $role
     */
    public function assignRole($role){
        $auth = $this->authManager;
        $action = $this->mapArray($role, function ($role) use($auth){
            $roleId = $this->parseRoleId($role);
            $role = $this->getRole($roleId);
            $check = $auth->getAssignment($roleId, $this->getId());
            return !$check ? $this->authManager->assign($role, $this->getId()) : false;
        });

        return $action;
    }

    /**
     * Revokes the given role from the user.
     *
     * @param  collection|object|array|string|int $role
     * @return bool
     */
    public function revokeRole($role)
    {
        return $this->mapArray($role, function ($role) {
            $roleId = $this->parseRoleId($role);
            $roleObj = $this->getRole($roleId);
            return $roleObj ? $this->authManager->revoke($roleObj, $this->getId()) : false;
        });
    }

    public function revokeAllRoles(){
        return $this->authManager->revokeAll($this->getId());
    }

    public function createPermission($permission){

    }

    public function removeAllPermissions(){
        return $this->authManager->removeAllPermissions();
    }



    /**
     * Syncs the given role(s) with the user.
     *
     * @param  collection|object|array|string|int $roles
     * @return bool
     */
    public function syncRoles($roles)
    {

    }

    /*
    |----------------------------------------------------------------------
    | Protected Methods
    |----------------------------------------------------------------------
    |
    */

    protected function fill($source, array $model){
        foreach ($model as $k => $val){
            if(is_object($source)){
                $source->$k = $val;
            } else {
                $source[$k] = $val;
            }
        }
        return $source;
    }

    public function isWithOr($slug)
    {
        foreach ($slug as $check) {
            if ( $this->is($check) ) return true;
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

    protected function parseOperator($str)
    {
        if ( is_array($str) ) { $str = implode(',', $str); }

        if ( preg_match('/([,|])(?:\s+)?/', $str, $m) ) {
            return $m[1] == '|' ? 'or' : 'and';
        }

        return false;
    }

    protected function hasDelimiterToArray($str)
    {
        if ( is_string($str) && preg_match('/[,|]/is', $str) ) {
            return preg_split('/ ?[,|] ?/', strtolower($str));
        }
        return is_array($str) ?
            array_filter($str, 'strtolower') : is_object($str) ?
                $str : strtolower($str);
    }

    /**
     * @param                   $item
     * @param callable|\Closure $closure
     *
     * @return array
     */
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

    /**
     * Parses role id from object, array
     * or a string.
     *
     * @param object|array|string|int $role
     * @return int
     */
    protected function parseRoleId($role)
    {
        if ( is_object($role) ) {
            $role = $role->name;
        }

        if ( is_string($role) || is_numeric($role) ) {
            // Kiểm tra role id có tồn tại k?
        }
        return (string) $role;
    }

    protected function withArrEmpty($arr) {
        if(is_array($arr)){
            if(empty(array_filter($arr))){
                return false;
            }
            return true;
        }
        return $arr;

    }
}