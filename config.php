<?php

use Dotenv\Dotenv;


$dotenv = Dotenv::createImmutable(__DIR__, '.env');
$dotenv->load();

return [
    'api_key'      => $_ENV['TELEGRAM_BOT_TOKEN'],
    //'bot_username' => "sd",//,
    'botname' => $_ENV['TELEGRAM_BOT_USERNAME'],
    'secret' => 'asd',

    'commands'     => [
        'paths'   => [
             __DIR__ . '/Modules/MainMenu',
             __DIR__ . '/Modules/StorySite/Commands',
        ],

    ],

    'admins'       => [
        $_ENV['TELEGRAM_ADMIN_ID'],
    ],

    'paths'        => [
        'download' => __DIR__ . '/Download',
        'upload'   => __DIR__ . '/Upload',
    ],

    'limiter'      => [
        'enabled' => true,
    ],
];