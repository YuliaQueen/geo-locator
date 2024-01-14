<?php

namespace Qween\Location\Component\Geolocator;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Client\ClientInterface;

class ApiGeoLocationLocator implements LocatorInterface
{
    private ClientInterface $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @throws GuzzleException
     */
    public function locate(IpInterface $ip): ?Location
    {
        $url = 'https://api.ipgeolocation.io/ipgeo?' . http_build_query(
                [
                    'apiKey' => $_ENV['IP_GEOLOCATION_API_KEY'],
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