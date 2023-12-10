<?php

namespace Qween\Location\Component\Geolocator;

interface LocatorInterface
{
    public function locate(string $ip): ?Location;
}