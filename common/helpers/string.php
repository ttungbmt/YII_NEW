<?php
if (! function_exists('mb_ucwords')) {
    function mb_ucwords ($string)
    {
        return mb_convert_case ($string, MB_CASE_TITLE, 'UTF-8');
    }
}
