<?php

namespace Qween\Location\Component\Geolocator;

use InvalidArgumentException;

final class Ip implements IpInterface
{
    private string $value;

    public function __construct(
        string $ip
    )
    {
        if (empty($ip)) {
            throw new InvalidArgumentException('ip cannot be empty');
        }
        if (filter_var($ip, FILTER_VALIDATE_IP) === false) {
            throw new InvalidArgumentException('ip is invalid');
        }
        $this->value = $ip;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}