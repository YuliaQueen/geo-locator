<?php

namespace Qween\Location\Component\Geolocator;

use GuzzleHttp\Client;

class Locator implements LocatorInterface
{
    public function locate(IpInterface $ip): ?Location
    {
        $url = 'https://api.ipgeolocation.io/ipgeo?' . http_build_query(
                [
                    'apiKey' => $_ENV['IP_GEOLOCATOR_API_KEY'],
                    'ip'     => $ip->getValue()
                ]
            );

        $client = new Client();
        $cURL = $client->get($url);

        $response = json_decode($cURL->getBody()->getContents(), true);

        $data = array_map(fn($item) => $item === '-' ? null : $item, $response);

        if (empty($data['country_name'])) {
            return null;
        }

        return new Location($data['country_name'], $data['state_prov'], $data['city']);
    }
}