<?php

namespace Qween\Location\Services;

interface LocatorInterface
{
    public function locate(string $ip): ?Location;
}