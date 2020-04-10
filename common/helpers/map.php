<?php

if ( ! function_exists('L'))
{
    function L($type, $props, array $children = null){
        $com = array_merge(is_null($props) ? [] : $props, ['_type' => $type]);
        if(empty($children)) return $com;

        return array_merge($com, ['children' => $children]);
    }
}

