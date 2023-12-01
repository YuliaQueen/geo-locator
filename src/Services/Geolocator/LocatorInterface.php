<?php

namespace Qween\Location\Services\Geolocator;

interface LocatorInterface
{
    public function locate(string $ip): ?Location;
}