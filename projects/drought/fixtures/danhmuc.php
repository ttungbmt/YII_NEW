<?php

$data['dm_folder'] = [
    'ndvi' => 'NDVI',
    'lst' => 'LST',
    'spi' => 'SPI',
    'other' => 'Khác'
];

$data['dm_noi_ph'] = [
    '1' => 'Khu cách ly',
    '2' => 'Bệnh viện',
    '3' => 'Cộng đồng',
];



$data['nav_links'] = [
    [
        'path' => '/maps',
        'name' => 'Bản đồ',
        'icon' => 'icon-map5',
    ],
    [
        'path' => '/maps/choropleth',
        'name' => 'Thống kê',
        'icon' => 'icon-stats-bars ',
        'visible' => user()->is('admin'),
    ],
    [
        'path'  => '/admin',
        'name'  => 'Quản lý dữ liệu',
        'icon'  => 'icon-clipboard3',
        'attrs' =>
            [
                'target' => '_blank',
            ],
    ],
];

$data['layer_keys'] = [
    'vungrau_ap' => 'vungrau_ap',
    'vungrau_qh_xa' => 'vungrau_qh_xa',
    'vungrau' => 'vungrau',
    'vungrau_vietgap' => 'vungrau',
    'vungrau_mdt' => 'vungrau',
    'nongho' => 'nongho',
    'maudat' => 'maudat',
    'pg_thonhuong' => 'pg_thonhuong',
    'poi_kdrauqua' => 'poi_kdrauqua',
    'poi_vattunn' => 'poi_vattunn',
    'poi_hoptacxa' => 'poi_hoptacxa',
    'hk_cssx' => 'hk_cssx',
    'hk_nongho' => 'hk_nongho',
    'hk_vung' => 'hk_vung',
];

$data['layer_meta'] = [
    'nongho' => [
        'label' => 'Hộ sản xuất',
        'table' => 'nongho',
    ],
    'maudat' => [
        'label' => 'Mẫu đất, nước',
        'table' => 'v_maudatnuoc'
    ],
    'poi_hoptacxa' => [
        'label' => 'Hợp tác xã',
        'table' => 'poi_hoptacxa'
    ],
    'poi_kdrauqua' => [
        'label' => 'Kinh doanh rau quả',
        'table' => 'poi_kdrauqua'
    ],
    'poi_vattunn' => [
        'label' => 'Cửa hàng/ Doanh nghiệp Vật tư nông nghiệp',
        'table' => 'poi_vattunn'
    ]
];

$data['layer_tree_hk'] = [
    [
        'title' => 'Ranh giới hành chính',
        'folder' => true
    ],
    [
        'title'     => 'Vùng hoa kiểng',
        'component' => [
            'url'    => '/geoserver/ows?',
            'layers' => 'hoakieng:hk_vung',
        ],
        "selected"  => true,
    ],
    [
        'title'     => 'Chủng loại hoa kiểng',
        'key'       => 'hk_nongho',
        'component' => [
            'url'    => '/geoserver/ows?',
            'layers' => 'hoakieng:v_hk_nongho',
        ],
        'folder'    => true,
        'expanded'  => true,
        'filter'    => [
            ['title' => 'Hoa lan', 'condition' => "loaicaytrong='HL' OR loai_cay LIKE 'HL%'", 'icon' => '/raumau/assets/icon/HL.png'],
            ['title' => 'Hoa mai', 'condition' => "loaicaytrong='HM' OR loai_cay LIKE 'HM%'", 'icon' => '/raumau/assets/icon/HM.png'],
            ['title' => 'Hoa nền', 'condition' => "loaicaytrong='HN' OR loai_cay LIKE 'HN%'", 'icon' => '/raumau/assets/icon/HN.png'],
            ['title' => 'Hoa kiểng', 'condition' => "loaicaytrong='HK' OR loai_cay LIKE 'HK%'", 'icon' => '/raumau/assets/icon/HK.png'],
            ['title' => 'Kiểng khác', 'condition' => "loaicaytrong='K' OR loai_cay IS NULL", 'icon' => '/raumau/assets/icon/KHAC.png'],
        ]
    ],
//    [
//        'title'     => 'Hình thức sản xuất',
//        'component' => [
//            'url'    => '/geoserver/ows?',
//            'layers' => '',
//        ],
//        'folder'    => true,
//        'expanded'  => true,
//        'filter'    => [
//            ['title' => 'Thông thường', 'condition' => ''],
//            ['title' => 'Nhà lưới', 'condition' => ''],
//            ['title' => 'Nhà màng', 'condition' => ''],
//            ['title' => 'Công nghệ cao', 'condition' => ''],
//        ]
//    ],
    [
        'title'     => 'Địa điểm sản xuất',
        'key'       => 'hk_cssx',
        'component' => [
            'url'    => '/geoserver/ows?',
            'layers' => 'hoakieng:hk_cssx',
        ],
        'folder'    => true,
        'expanded'  => true,
        'filter'    => [
            ['title' => 'Hộ dân', 'condition' => '1=1'],
            ['title' => 'Tổ hợp tác xã', 'condition' => '1=1'],
            ['title' => 'Hợp tác xã', 'condition' => '1=1'],
            ['title' => 'Công ty', 'condition' => '1=1'],
        ]
    ],
    [
        'title'     => 'Địa điểm kinh doanh hoa kiểng',
        'component' => [
            'url'    => '/geoserver/ows?',
            'layers' => '',
        ],
        'folder'    => true,
        'expanded'  => false,
        'filter'    => [
            ['title' => 'Hoa lan', 'condition' => '1=1'],
            ['title' => 'Kiểng thông thường', 'condition' => '1=1'],
            ['title' => 'Kiểng cổ thụ', 'condition' => '1=1'],
        ]
    ],
    [
        'title'     => 'Cửa hàng vật tư nông nghiệp',
        'component' => [
            'url'    => '/geoserver/ows?',
            'layers' => '',
        ],
        'folder'    => true,
    ],
    [
        'title'     => 'Quy hoạch cây trồng vật nuôi',
        'component' => [
            'url'    => '/geoserver/ows?',
            'layers' => '',
        ],
        'folder'    => true,
    ],
];

$data['layer_tree'] = [
//    [
//        "title"    => "Địa hình",
//        "key"      => "diahinh",
//        "folder"   => true,
//        "children" => [
//            [
//                "title"     => "Quận huyện",
//                "key"       => "pg_ranhquan",
//                "component" => [
//                    "url"    => "/geoserver/ows?",
//                    "layers" => "dichte:dm_quan",
//                ],
//            ],
//            [
//                "title"     => "Phường xã",
//                "key"       => "dm_phuong_vn",
//                "component" => [
//                    "url"    => "/geoserver/ows?",
//                    "layers" => "dichte:dm_phuong",
//                ],
//            ],
//            [
//                "title"     => "Ranh tổ",
//                "key"       => "pg_ranhto",
//                "component" => [
//                    "url"    => "/geoserver/ows?",
//                    "layers" => "hcm_map:pg_ranhto",
//                ],
//            ],
//            [
//                "title"     => "Giao thông",
//                "key"       => "ln_giaothong",
//                "component" => [
//                    "url"    => "/geoserver/ows?",
//                    "layers" => "hcm_map:gt_ln",
//
//                ],
//            ],
//            [
//                "title"     => "Thủy hệ",
//                "key"       => "pg_thuyhe",
//                "component" => [
//                    "url"    => "/geoserver/ows?",
//                    "layers" => "hcm_map:pg_thuyhe",
//                ],
//            ]
//        ],
//    ],
//    [
//        "title"    => "Địa chính",
//        "key"      => "diachinh",
//        "folder"   => true,
//        "children" => [
//            [
//                "title"     => "Ranh thửa",
//                "key"       => "pg_ranhthua",
//                "component" => [
//                    "component" => [
//                        "cql_filter" => "ten_quan IN ('Bình Chánh', 'Hóc Môn', 'Củ Chi')"
//                    ],
//                    "url"       => "",
//                    "layers"    => "hcm_map:pg_ranhthua",
//                ],
//
//            ]
//        ]
//    ],
//    [
//        "title"    => "Vùng rau",
//        "key"      => null,
//        "folder"   => true,
//        "expanded" => true,
//        "children" => [
//            [
//                "title"     => "Vùng rau theo ấp",
//                "key"       => "vungrau_ap",
//                "component" => [
//                    "name"   => 'SingleWMSTileLayer',
//                    "url"    => "/geoserver/ows?",
//                    "layers" => "raumau:v_raumau_2016",
//                    "zIndex" => 180,
//
//                ],
//            ],
//            [
//                "title"     => "Vùng rau theo xã",
//                "key"       => "vungrau_qh_xa",
//                "component" => [
//                    "name"   => 'SingleWMSTileLayer',
//                    "url"    => "/geoserver/ows?",
//                    "layers" => "raumau:v_raumau_qh",
//                    "zIndex" => 170,
//                ],
//            ],
//            [
//                "title"     => "Vùng rau thực tế",
//                "key"       => LayerConst::VUNGRAU,
//                "component" => [
//                    "name"   => 'SingleWMSTileLayer',
//                    "url"    => "/geoserver/ows?",
//                    "layers" => "raumau:v_raumau",
//                    "zIndex" => 200,
//                ],
//                "selected"  => true,
//            ],
//            [
//                "title"     => "Vùng rau VietGAP",
//                "key"       => "vungrau_vietgap",
//                "component" => [
//                    "name"   => 'SingleWMSTileLayer',
//                    "url"    => "/geoserver/ows?",
//                    "layers" => "raumau:v_raumau",
//                    "data"   => [
//                        "cql_filter" => "dt_vietgap > 0",
//                    ],
//                    "zIndex" => 200,
//                ],
//            ],
//            [
//                "title"     => "Vùng rau khác",
//                "key"       => "vungrau_khac",
//                "component" => [
//                    "name"   => 'SingleWMSTileLayer',
//                    "url"    => "/geoserver/ows?",
//                    "layers" => "raumau:v_raumau",
//                    "zIndex" => 200,
//                ],
//            ],
//        ],
//    ],
//    [
//        "title"     => "Hình thức canh tác",
//        "key"       => "vungrau_mdt",
//        "expanded"  => true,
//        "component" => [
//            "name"   => 'SingleWMSTileLayer',
//            "url"    => "/geoserver/ows?",
//            "layers" => "raumau:v_raumau",
//            "styles" => 'mohinh_ct',
//            "zIndex" => 2000,
//        ],
//        "filter"    => [
//            ["title" => 'Truyền thống', "condition" => 'mohinh_ct=1'],
//            ["title" => 'Công nghệ cao một phần', "condition" => 'mohinh_ct=2'],
//            ["title" => 'Công nghệ cao', "condition" => 'mohinh_ct=3'],
//        ]
//    ],
//    [
//        "title"     => "Hộ sản xuất",
//        "key"       => LayerConst::HO_SX,
//        "expanded"  => true,
//        "icon"      => '/raumau/assets/img/household.png',
//        "component" => [
//            // "name" => 'SingleWMSTileLayer',
//            "url"    => "/geoserver/ows?",
//            "layers" => "raumau:v_hosx",
//            "zIndex" => 2000,
////            'maxZoom' => 12
//        ],
//        "filter"    => [
//            ["title" => 'Truyền thống', "condition" => 'mohinh_ct=1'],
//            ["title" => 'Công nghệ cao một phần', "condition" => 'mohinh_ct=2'],
//            ["title" => 'Công nghệ cao', "condition" => 'mohinh_ct=3'],
//        ]
//    ],
//    [
//        "title"     => "Mẫu đất, nước",
//        "key"       => LayerConst::MAUDAT,
//        "expanded"  => true,
//        "icon"      => '/raumau/assets/img/maudat.png',
//        "component" => [
//            "url"    => "/geoserver/ows?",
//            "layers" => "raumau:v_maudatnuoc",
//            "zIndex" => 2000,
//        ],
//        "filter"    => [
//            ["title" => 'Đạt đất, nước', "condition" => 'dat_dat=1 AND dat_nuoc=1', "icon" => '/raumau/assets/img/maudat_dat.png',],
//            ["title" => 'Nhiễm đất, nước', "condition" => 'dat_dat=0 AND dat_nuoc=0', "icon" => '/raumau/assets/img/maudat_kdat.png',],
//            ["title" => 'Nhiễm đất', "condition" => 'dat_dat=0 AND dat_nuoc=1', "icon" => '/raumau/assets/img/maudat_nhiemdat.png',],
//            ["title" => 'Nhiễm nước', "condition" => 'dat_dat=1 AND dat_nuoc=0', "icon" => '/raumau/assets/img/maudat_nhiemnc.png',]
//        ]
//    ],
//    [
//        "title"     => "Thổ nhưỡng",
//        "key"       => "pg_thonhuong",
//        "component" => [
//            "url"    => "/geoserver/ows?",
//            "layers" => "raumau:pg_thonhuong",
//            "zIndex" => 150,
//        ],
//    ],
//    [
//        "title"    => "Cửa hàng",
//        "key"      => "cuahang",
//        "folder"   => true,
//        "children" => [
//            [
//                "title"     => "Kinh doanh rau quả",
//                "key"       => LayerConst::POI_KDRAUQUA,
//                "icon"      => '/raumau/assets/img/store.png',
//                "component" => [
//                    "url"    => "/geoserver/ows?",
//                    "layers" => "raumau:poi_kdrauqua",
//                    "zIndex" => 2000,
//                ],
//            ],
//            [
//                "title"     => "Vật tư nông nghiệp",
//                "key"       => LayerConst::POI_VATTUNN,
//                "icon"      => '/raumau/assets/img/vattunn.png',
//                "component" => [
//                    "url"    => "/geoserver/ows?",
//                    "layers" => "raumau:poi_vattunn",
//                    "zIndex" => 2000,
//                ],
//            ],
//        ],
//    ],
//    [
//        "title"     => "Hợp tác xã",
//        "key"       => LayerConst::POI_HOPTACXA,
//        "expanded"  => true,
//        "icon"      => '/raumau/assets/img/hoptacxa.png',
//        "component" => [
//            "url"    => "/geoserver/ows?",
//            "layers" => "raumau:v_hoptacxa",
//            "zIndex" => 2000,
//        ],
//        "folder"    => false,
//        "filter"    => [
//            ["title" => 'HTX nông nghiệp', "condition" => 'loai_htx=1'],
//            ["title" => 'HTX khác', "condition" => 'loai_htx=0'],
//        ]
//    ],
//    [
//        "title"    => "Quy hoạch CSHT",
//        "key"      => "qh_csht",
//        "folder"   => true,
//        "children" => [
//            [
//                "title"     => "năm 2030",
//                "key"       => "qh_2030",
//                "component" => [
//                    "url"    => "/geoportal/ows?",
//                    "layers" => "raumau:qh_cosoht",
//                    "zIndex" => 100,
//                ],
//            ]
//        ],
//    ],
//    [
//        "title"    => "Định hướng SDĐ NN",
//        "key"      => "dh_sdd",
//        "folder"   => true,
//        "children" => [
//            [
//                "title"     => "năm 2020",
//                "key"       => "dh_2020",
//                "component" => [
//                    "url"    => "/geoportal/ows?",
//                    "layers" => "raumau:dh_2020",
//                    "zIndex" => 100,
//                ],
//            ],
//            [
//                "title"     => "năm 2025",
//                "key"       => "dh_2025",
//                "component" => [
//                    "url"    => "/geoportal/ows?",
//                    "layers" => "raumau:dh_2025",
//                    "zIndex" => 100,
//                ],
//            ],
//            [
//                "title"     => "năm 2030",
//                "key"       => "dh_2030",
//                "component" => [
//                    "url"    => "/geoportal/ows?",
//                    "layers" => "raumau:dh_2030",
//                    "zIndex" => 100,
//                ],
//                "folder"    => false,
//                "children"  => null,
//            ]
//        ],
//    ],
//    [
//        "title"    => "Quy hoạch cây trồng, vật nuôi",
//        "key"      => "qh_caytrong",
//        "folder"   => true,
//        "children" => [
//            [
//                "title"     => "năm 2020",
//                "key"       => "qh_2020",
//                "component" => [
//                    "url"    => "/geoportal/ows?",
//                    "layers" => "raumau:qh_2020",
//                    "zIndex" => 100,
//                ],
//            ],
//            [
//                "title"     => "năm 2025",
//                "key"       => "qh_2025",
//                "component" => [
//                    "url"    => "/geoportal/ows?",
//                    "layers" => "raumau:qh_2025",
//                    "zIndex" => 100,
//                ],
//
//            ],
//            [
//                "title"     => "năm 2030",
//                "key"       => "qh_2030",
//                "component" => [
//                    "url"    => "/geoportal/ows?",
//                    "layers" => "raumau:qh_2030",
//                    "zIndex" => 100,
//                ],
//            ]
//        ],
//    ]
];


return $data;