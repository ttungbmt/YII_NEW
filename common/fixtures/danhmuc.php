<?php

$data['yesno'] = [
    1 => 'Có',
    0 => 'Không',
];

$data['dm_quan'] = [
    '760' => 'Quận 1',
    '769' => 'Quận 2',
    '770' => 'Quận 3',
    '773' => 'Quận 4',
    '774' => 'Quận 5',
    '775' => 'Quận 6',
    '778' => 'Quận 7',
    '776' => 'Quận 8',
    '763' => 'Quận 9',
    '771' => 'Quận 10',
    '772' => 'Quận 11',
    '761' => 'Quận 12',
    '777' => 'Quận Bình Tân',
    '765' => 'Quận Bình Thạnh',
    '764' => 'Quận Gò Vấp',
    '768' => 'Quận Phú Nhuận',
    '766' => 'Quận Tân Bình',
    '767' => 'Quận Tân Phú',
    '762' => 'Quận Thủ Đức',
    '785' => 'Huyện Bình Chánh',
    '787' => 'Huyện Cần Giờ',
    '783' => 'Huyện Củ Chi',
    '784' => 'Huyện Hóc Môn',
    '786' => 'Huyện Nhà Bè',
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

return $data;