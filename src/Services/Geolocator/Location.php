<?php

namespace Qween\Location\Services\Geolocator;

class Location
{
    public function __construct(
        private string $country,
        private ?string $region,
        private ?string $city
    )
    {
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }
}