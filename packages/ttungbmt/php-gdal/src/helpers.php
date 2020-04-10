<?php
if (!function_exists('viewConsole')) {
    /**
     * Show html console
     *
     * @param  string $output
     * @return string
     */
    function viewConsole(string $output){
        return nl2br(str_replace('  ', ' &nbsp;', htmlentities($output)));
    }
}
