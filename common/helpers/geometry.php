<?php

if (!function_exists('box2bound')) {
    function box2bound($box)
    {
        if (empty($box)) return [];

        $pattern = '/([0-9.]+)\s([0-9.]+),([0-9.]+)\s([0-9.]+)/';
        preg_match($pattern, $box, $matches);
        return [
            [$matches[2], $matches[1]],
            [$matches[4], $matches[3]],
        ];
    }
}

function toFeature($data)
{
    if (!isAssoc($data)) {
        return array_map(function ($v) {return toFeature($v);}, $data);
    }

    $data = collect($data);

    return [
        'type'       => 'Feature',
        'properties' => $data->except(['geometry'])->all(),
        'geometry'   => json_decode($data->get('geometry'), true),
    ];
}

;