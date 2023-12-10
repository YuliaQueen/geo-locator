<?php

namespace Qween\Location\Component\Geolocator;

use GuzzleHttp\Client;
use InvalidArgumentException;

class Locator implements LocatorInterface
{
    public function locate(string $ip): ?Location
    {
        if (empty($ip)) {
            throw new InvalidArgumentException('ip cannot be empty');
        }
        if (filter_var($ip, FILTER_VALIDATE_IP) === false) {
            throw new InvalidArgumentException('ip is invalid');
        }

        $url = 'https://api.ipgeolocation.io/ipgeo?' . http_build_query(
                [
                    'apiKey' => $_ENV['IP_GEOLOCATOR_API_KEY'],
                    'ip'     => $ip
                ]
            );

        $client = new Client();
        $cURL = $client->get($url);

        $response = json_decode($cURL->getBody()->getContents(), true);

        $data = array_map(function ($value) {
            return $value !== '-' ? $value : null;
        }, $response);

        if (!isset($data['country_name'])) {
            return null;
        }

        return new Location($data['country_name'], $data['state_prov'], $data['city']);
    }
}