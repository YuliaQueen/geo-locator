<?php

namespace Qween\Location\Component\Geolocator;

use Psr\Http\Client\ClientInterface;

class Locator implements LocatorInterface
{
    public function __construct(
        private ClientInterface $client
    )
    {
    }

    public function locate(IpInterface $ip): ?Location
    {
        $url = 'https://api.ipgeolocation.io/ipgeo?' . http_build_query(
                [
                    'apiKey' => $_ENV['IP_GEOLOCATOR_API_KEY'],
                    'ip'     => $ip->getValue()
                ]
            );

        $response = $this->client->get($url);

        $response = json_decode($response->getBody()->getContents(), true);

        $data = array_map(fn($item) => $item === '-' ? null : $item, $response);

        if (empty($data['country_name'])) {
            return null;
        }

        return new Location($data['country_name'], $data['state_prov'], $data['city']);
    }
}