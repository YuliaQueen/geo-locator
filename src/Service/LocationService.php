<?php

namespace Qween\Location\Service;

use Qween\Location\Component\Geolocator\Ip;
use Qween\Location\Component\Geolocator\Location;
use Qween\Location\Component\Geolocator\LocatorInterface;

class LocationService
{
    public function __construct(
        private LocatorInterface $locator
    )
    {
    }

    public function locate(Ip $ip): ?Location
    {
        return $this->locator->locate($ip);
    }
}