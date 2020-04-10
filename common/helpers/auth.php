<?php

if ( ! function_exists('auth'))
{
    function auth(){
        return \Yii::$app->authManager;
    }
}


if ( ! function_exists('user'))
{
    function user(){
        return \Yii::$app->user;
    }
}

if ( ! function_exists('role'))
{
    function role(...$args){
        return \Yii::$app->user->is(...$args);
    }
}

if ( ! function_exists('hasRoles'))
{
    function hasRoles(...$args){
        return \Yii::$app->user->hasRoles(...$args);
    }
}

if ( ! function_exists('can'))
{
    function can(...$args){
        return Yii::$app->user->hasPerms(...$args);
    }
}

if ( ! function_exists('roleCond'))
{
    function roleCond(){
        return \common\supports\RoleManager::init();
    }
}

if ( ! function_exists('userInfo'))
{
    function userInfo(){
        return optional(data_get(user(), 'identity.info'));
    }
}

if ( ! function_exists('getRoles'))
{
    function getRoles($userId = null){
//        session(['roles' => []]);
        if(!session('roles')){
            session(['roles' => array_keys(auth()->getRolesByUser($userId ? $userId : user()->id))]);
        }
        return session('roles');
    }
}



