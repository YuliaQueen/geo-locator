<?php

namespace Qween\Location\Component\Geolocator;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Client\ClientInterface;

class ApiInfoLocator implements LocatorInterface
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
        $ipValue = $ip->getValue();

        $url = "ipinfo.io/$ipValue?" . http_build_query(
                [
                    'token' => $_ENV['IP_INFO_API_KEY']
                ]
            );

        $response = $this->client->get($url);

        $response = json_decode($response->getBody()->getContents(), true);

        $data = array_map(fn($item) => $item === '-' ? null : $item, $response);

        if (empty($data['country'])) {
            return null;
        }

        return new Location($data['country'], $data['region'], $data['city'], $ipValue);
    }
}