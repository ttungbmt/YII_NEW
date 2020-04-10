<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'i48nstrJYQRKuDK6IipRFfbSpqQELiFz',
        ],
        'db' => require(__DIR__.'/db.php'),
    ],
];

return $config;
