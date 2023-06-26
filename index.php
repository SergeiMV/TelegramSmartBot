#!/usr/bin/env php
<?php

use TelegramSmartBot\Tools\Logger\LoggerBuilder;
use NPM\TelegramBotManager\BotManager;

require_once __DIR__ . '/vendor/autoload.php';

include 'bootstrap.php';

/** @var array $config */
$config = require __DIR__ . '/config.php';


$logger = (new LoggerBuilder)->getLogger('TelegraBot');

try {
    $bot = new BotManager($config);

    $bot->run();

} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    $logger->error($e->getMessage());
}

