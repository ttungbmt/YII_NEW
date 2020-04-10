<?php

use Carbon\Carbon;
use Carbon\CarbonPeriod;

if (! function_exists('to_db_date')) {
    function to_db_date($in)
    {
        try {
            return Carbon::createFromFormat('d/m/Y', $in);
        } catch (\Exception $e){
            return null;
        }
    }
}

if (! function_exists('validateDate')) {
    function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}



if (! function_exists('from_db_date')) {
    function from_db_date($in)
    {
        try {
            return Carbon::createFromFormat('Y-m-d', $in);
        } catch (\Exception $e){
            return null;
        }
    }
}

if (! function_exists('dateRange')) {
    function dateRange( $first, $last, $period = '1 month', $format = 'Y-m' ) {

        $dates = [];
        $period = CarbonPeriod::create($first, $period, $last);

        foreach ($period as $key => $date) {
            $dates[] = $date->format($format);
        }

        return $dates;
    }
}



