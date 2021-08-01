<?php

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class LoggerFactory
{
    public static function constructLogger(): LoggerInterface
    {
        // create a log channel
        $log = new Logger('name');
        $log->pushHandler(new StreamHandler(getcwd().DIRECTORY_SEPARATOR.'logs'.DIRECTORY_SEPARATOR.'.resource-log', Logger::WARNING));

        return $log;
    }
}
