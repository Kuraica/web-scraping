<?php

namespace App\Logging;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

class HourlyLogRotation
{
    public function __invoke(array $config)
    {
        $logger = new Logger('hourly_logs');

        $hourlyLogFile = storage_path('logs/' . date('Y-m-d') . '/hourly-' . date('H') . '.log');
        $handler = new StreamHandler($hourlyLogFile, Logger::DEBUG);

        // Dodavanje formatera za bolje formatiranje
        $formatter = new LineFormatter(null, null, true, true);
        $handler->setFormatter($formatter);

        $logger->pushHandler($handler);

        return $logger;
    }
}