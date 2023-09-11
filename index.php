#!/usr/bin/env php
<?php

use Dotenv\Dotenv;
use SergiX44\Nutgram\Configuration;
use SergiX44\Nutgram\Nutgram;
use TelegramSmartBot\Modules\MainMenu\MainMenuCommand;
use TelegramSmartBot\Tools\Logger\LoggerFactory;

require_once __DIR__ . '/vendor/autoload.php';

$logger = (new LoggerFactory)->getLogger('telegram_bot');

$dotenv = Dotenv::createImmutable(__DIR__, '.env');
$dotenv->load();

try {
    $config = new Configuration(
        logger: $logger,
    );


    $bot = new Nutgram($_ENV['TELEGRAM_BOT_TOKEN'], $config);


    $commands = [
        MainMenuCommand::class,
    ];

    foreach ($commands as $command) {
        $class = new $command;
        if ($class->haveParams()) {
            $bot->onCommand($class->getCommand() . ' {param}', [$class, 'handle']);
            $bot->onCallbackQueryData($class->getCommand() . ' {param}', [$class, 'handle']);
        } else {
            $bot->onCommand($class->getCommand(), [$class, 'handle']);
            $bot->onCallbackQueryData($class->getCommand(), [$class, 'handle']);
        }
    }

    $bot->run();
} catch (Exception $e) {
    $logger->error($e->getMessage());
    $logger->error($e->getTraceAsString());
}

