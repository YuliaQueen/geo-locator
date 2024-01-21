<?php

namespace Qween\Location\Component\Geolocator;

use Monolog\Handler\BrowserConsoleHandler;
use Psr\Log\LoggerInterface;

class ErrorHandler
{
    private $logger;

    public function __construct(
        LoggerInterface $logger
    )
    {
        $this->logger = $logger;
    }

    public function handle(\Exception $e)
    {
        $this->logger->pushHandler(new BrowserConsoleHandler());
        $this->logger->error($e->getMessage());
    }
}