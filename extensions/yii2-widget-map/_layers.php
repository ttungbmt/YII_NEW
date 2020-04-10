<?php

return [
    [
        'type'      => 'base',
        'title'     => 'None',
        'component' => [
            'name' => 'TileLayer',
            'url'  => '',
        ]
    ],
    [
        'type'      => 'base',
        'title'     => 'HCMGIS',
        'component' => [
            'name'   => 'WMSTileLayer',
            'url'    => 'https://pcd.hcmgis.vn/geoserver/gwc/service/wms',
            'layers' => 'hcm_map:hcm_map_all'
        ]
    ],
    [
        'type'      => 'base',
        'title'     => 'Google',
        'checked'   => true,
        'component' => [
            'name'  => 'GoogleLayer',
            'type'  => 'roadmap',
            'tiled' => true
        ]
    ],
    [
        'type'      => 'base',
        'title'     => 'Mapbox',
        'component' => [
            'name'        => 'TileLayer',
            'map'         => 'mapbox.streets',
            'url'         => 'https://api.tiles.mapbox.com/v4/{map}/{z}/{x}/{y}.png?access_token={accessToken}',
            'accessToken' => 'pk.eyJ1IjoidHR1bmdibXQiLCJhIjoiY2EzNDFhZjU4ZThkNzY5NTU3M2U1YWFiNmY4OTE3OWQifQ.Bo1ss5J4UjPPOjmq9S3VQw',
            'attribution' => 'Map data &copy; <a href=\'http://mapbox.com\'>Mapbox</a>',
        ]
    ],
    [
        'type'      => 'base',
        'title'     => 'Ảnh hàng không',
        'component' => [
            'name' => 'TileLayer',
            'url'  => 'http://trueortho.hcmgis.vn/basemap/cache_lidar/{z}/{x}/{y}.jpg',
        ]
    ],
    [
        'type'      => 'base',
        'title'     => 'Ảnh vệ tinh',
        'component' => [
            'name'  => 'GoogleLayer',
            'type'  => 'satellite',
            'tiled' => true,
        ]
    ],
];