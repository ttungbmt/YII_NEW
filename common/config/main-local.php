<?php

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
//            'dsn' =>
//                env('DB_CONNECTION', 'pgsql').
//                ":host=".env('DB_HOST', '192.168.1.40').
//                ";dbname=".env('DB_DATABASE', 'yee').
//                ";port=".env('DB_PORT', '5432').";",
//            'username' => env('DB_USERNAME', 'postgres'),
//            'password' => env('DB_PASSWORD', 'postgres'),
            'charset' => 'utf8',
            'on afterOpen' => function($event) {
                //$event->sender->createCommand("SET time_zone = '+00:00'")->execute();
            },
            'enableQueryCache' => true,
            'queryCache' => 'filecache',
            'queryCacheDuration' => 3600
        ],
        'filecache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.mailtrap.io',
                'username' => 'e2404777e06f4d',
                'password' => '88526433d3a1f2',
                'port' => '2525',
                'encryption' => 'tls',
            ],
            'htmlLayout' => '@vendor/yeesoft/yii2-yee-auth/views/mail/layouts/html',
            'textLayout' => '@vendor/yeesoft/yii2-yee-auth/views/mail/layouts/text',
        ],
    ],
];
