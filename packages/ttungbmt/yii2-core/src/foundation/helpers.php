<?php

if (!function_exists('app')) {
    function app($abstract = null) {
        if (is_null($abstract)) {
            return Yii::$app;
        }

        return data_get(Yii::$app, $abstract);
    }
}

if (!function_exists('request')) {
    /**
     * Get an instance of the current request or an input item from the request.
     *
     * @param  array|string $key
     * @param null          $default
     * @return ttungbmt\web\Request|string|array
     */
    function request($key = null, $default = null) {

        if (is_null($key)) {
            return app('request');
        }

        if (is_array($key)) {
            return app('request')->only($key);
        }

        $value = app('request')->input($key);

        return is_null($value) ? value($default) : $value;
    }
}


