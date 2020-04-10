<?php
use LightnCandy\LightnCandy;

if (! function_exists('trans')) {
    /**
     * Translate the given message.
     *
     * @param  string|null  $template
     * @param  array|null  $data
     * @param  string|null  $locale
     * @return string|null
     */

    function trans(string $template = '', array $data = [], $locale = null){
        $php = LightnCandy::compile($template);
        return LightnCandy::prepare($php)($data);
    }
}