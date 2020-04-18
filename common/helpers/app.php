<?php

use common\libs\activitylog\ActivityLogger;
use Illuminate\Support\Str;
use yii\bootstrap\Html;
use yii\helpers\Url;

if (!function_exists('asset_manifest')) {
    function asset_manifest($path, $type){
        $manifestPath = Yii::getAlias('@webroot'.$path);
        $manifest = collect(json_decode(file_get_contents($manifestPath), true));
        $filter = function ($data, $search, $reverse = false){
            $data = $data ? $data : [];
            return array_filter($data, function ($e) use($search, $reverse){
                return $reverse ? !Str::containsAll($e, [$search]) : Str::containsAll($e, [$search]);
            });
        };
        $entrypoints = $filter($manifest->get('entrypoints'), 'runtime-main.js', true);
        $css = $filter($entrypoints, '.css');
        $js = $filter($entrypoints, '.js');

        return data_get([
            'css' => $css,
            'js' => $js
        ], $type);
    }
}

if (!function_exists('html_manifest')) {
    function html_manifest($path, $type){
        $html = '';
        $prefix = substr($path, 0, stripos($path, 'asset-manifest.json'));

        foreach (asset_manifest($path, $type) as $a){
            $html .= $type === 'css' ? Html::cssFile(Url::to($prefix.$a)) : Html::jsFile(Url::to($prefix.$a));
        }

        return $html;
    }
}

if (!function_exists('manifest')) {
    function manifest($path, $type){
        $manifestPath = Yii::getAlias('@webroot'.$path);
        $manifest = collect(json_decode(file_get_contents($manifestPath), true));
        $filter = function ($data, $search){
            return array_filter($data, function ($e) use($search){
                return !Str::containsAll($e, [$search]);
            });
        };
        $entrypoints = $filter($manifest->get('entrypoints'), 'runtime-main');
        $css = $filter($entrypoints, '.css');
        $js = $filter($entrypoints, '.js');

        return data_get([
            'css' => $css,
            'js' => $js
        ], $type);
    }
}

if (!function_exists('opt')) {
    function opt($item) {
        return is_array($item) ? optional((object)$item) : optional($item);
    }
}



if (!function_exists('url')) {
    function url(...$args) {
        return Url::to(...$args);
    }
}

if (!function_exists('params')) {
    function params($key = null, $default = null) {
        $params = app('params');
        return is_null($key) ? $params : data_get($params, $key, $default);
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token() {
        return app('request')->getCsrfToken();
    }
}

if (!function_exists('db_command')) {
    function db_command($sql = null, $params = []) {

        return app('db')->createCommand($sql, $params);
    }
}

if (!function_exists('lang')) {
    function lang(...$args) {
        return Yii::t('app', ...$args);
    }
}


if (!function_exists('aliases')) {
    function aliases(...$args) {
        return Yii::getAlias(...$args);
    }
}

if (!function_exists('d')) {
    function d($var) {
        $debug = (array)debug_backtrace(1);
        $caller = array_shift($debug);
        echo '<code>File: ' . $caller['file'] . ' / Line: ' . $caller['line'] . '</code>';
        dd($var);
    }
}

if (!function_exists('asset')) {
    function asset($url) {
        $params = app('components.view.theme');
        if (!empty($params)) {
            $theme = Yii::createObject(array_merge(['class' => 'yii\base\Theme'], $params));
            return $theme->getUrl($url);
        }
        return url($url);
    }
}




if (!function_exists('trans')) {
    function trans($category, $message, $params = [], $language = null) {
        return Yii::t($category, $message, $params, $language);
    }
}

if (!function_exists('activity')) {
    function activity(string $logName = null): ActivityLogger {
        $defaultLogName = 'default';

        return with(new ActivityLogger)->useLog($logName ?? $defaultLogName);
    }
}

if (!function_exists('session')) {
    function session($key = null, $default = null) {
        $session = Yii::$app->session;
        if (is_null($key)) {
            return $session;
        }

        if (is_array($key)) {
            return $session->put($key);
        }

        return $session->get($key, $default);
    }
}


if (!function_exists('api')) {
    function api($url = null) {
        if (is_null($url)) {
            return app('api');
        }

        return app('api')->get($url);
    }
}

if (!function_exists('errorSummary')) {
    function errorSummary($models, $options = []) {
        return Html::errorSummary($models, array_merge([
            'class'  => 'alert alert-danger no-border',
            'header' => (
                '<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>' .
                '<p class="text-semibold">Vui lòng sửa các lỗi sau đây: </p>'
            )
        ], $options));
    }
}

if (!function_exists('getUserIpAddr')) {
    function getUserIpAddr() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            //ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            //ip pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

}

function isHttps() {
    return Yii::$app->request->isSecureConnection;

//    if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) {
//
//        return true;
//    }
//    return false;
}





