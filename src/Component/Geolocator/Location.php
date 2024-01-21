<?php

namespace Qween\Location\Component\Geolocator;

class Location
{
    public function __construct(
        private string  $country,
        private ?string $region,
        private ?string $city,
        private ?string $ip
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

    public function getIp(): ?string
    {
        return $this->ip;
    }
}