<?php

namespace Qween\Location\Component\Geolocator;

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
        $this->logger->error($e->getMessage());
    }
}