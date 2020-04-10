<?php

if ( ! function_exists('_number_format'))
{
    function _number_format($number , $decimals = 1 , $dec_point = ',' , $thousands_sep = '.' ){
        return number_format($number, ((int) $number == $number ? 0 : $decimals), $dec_point, $thousands_sep);
    }
}

if ( ! function_exists('toHecta'))
{
    function toHecta($number){
        return _number_format($number/10000);
    }
}

