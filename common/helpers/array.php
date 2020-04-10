<?php

if (! function_exists('isAssoc')) {
    function isAssoc(array $array)
    {
        $keys = array_keys($array);

        return array_keys($keys) !== $keys;
    }
}

if (! function_exists('array_filter_blank')) {
    function array_filter_blank(array $array)
    {
        return array_filter($array, function ($item){
            return trim($item) !== '';
        });
    }
}
