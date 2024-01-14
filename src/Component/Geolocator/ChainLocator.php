<?php

namespace Qween\Location\Component\Geolocator;

use GuzzleHttp\Exception\GuzzleException;

class ChainLocator implements LocatorInterface
{
    private array $locators;

    public function __construct(
        ...$locators
    )
    {
        $this->locators = $locators;

        if (empty($locators)) {
            throw new \InvalidArgumentException('No locator provided');
        }
    }

    public function locate(IpInterface $ip): ?Location
    {
        $result = null;
        foreach ($this->locators as $locator) {
            $location = $locator->locate($ip);

            if ($location === null) {
                continue;
            }

            if ($location->getCity() !== null) {
                return $location;
            }

            if ($result === null || $location->getRegion() !== null) {
                $result = $location;
            }
        }

        return $result;
    }
}