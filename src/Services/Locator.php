<?php

namespace Qween\Location\Services;

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
                    'apiKey' => 'a7b7a7d985d1426eac96e6294a002905',
                    'ip'     => $ip
                ]
            );

        $cURL = curl_init();

        curl_setopt($cURL, CURLOPT_URL, $url);
        curl_setopt($cURL, CURLOPT_HTTPGET, true);
        curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURL, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json',
            'User-Agent: ' . $_SERVER['HTTP_USER_AGENT']
        ));

        $response = json_decode(curl_exec($cURL), true);

        $data = array_map(function ($value) {
            return $value !== '-' ? $value : null;
        }, $response);

        if (!isset($data['country_name'])) {
            return null;
        }

        return new Location($data['country_name'], $data['state_prov'], $data['city']);
    }
}