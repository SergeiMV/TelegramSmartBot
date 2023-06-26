<?php

namespace TelegramSmartBot\Tools\Logger;
use Bramus\Monolog\Formatter\ColoredLineFormatter;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

require 'vendor/autoload.php';


class LoggerBuilder
{

    private string $textFormat = "[%datetime%] [%channel%] %level_name%: %message%";

    private string $dateFormat = "Y-m-d H:i:s";

    private string $logConsole = 'php://stdout';

    private string $logFile = 'logs/app.log';

    private bool $isLogToConsole = true;

    private bool $isLogToFile = true;


    public function getLogger($loggerName) : LoggerInterface
    {
        $logger = new Logger($loggerName);

        assert($this->isLogToConsole || $this->isLogToFile, 'No logging stream has been selected.');

        if ($this->isLogToConsole) {
            $consoleHandler = new StreamHandler($this->logConsole, Logger::DEBUG);
            $consoleHandler->setFormatter(new ColoredLineFormatter(null, $this->textFormat, $this->dateFormat));
            $logger->pushHandler($consoleHandler);
        }

        if ($this->isLogToFile) {
            $fileHandler = new RotatingFileHandler($this->logFile, 7, Logger::DEBUG);
            $fileHandler->setFormatter(new LineFormatter($this->textFormat, $this->dateFormat));
            $logger->pushHandler($fileHandler);
        }

        return $logger;
    }


    public function setLogToConsole(bool $isLogToConsole)
    {
        $this->isLogToConsole = $isLogToConsole;
    }


    public function setLogToFile(bool $isLogToFile)
    {
        $this->isLogToFile = $isLogToFile;
    }
}