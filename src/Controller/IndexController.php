<?php

namespace Qween\Location\Controller;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Qween\Location\Component\Geolocator\ApiGeoLocationLocator;
use Qween\Location\Component\Geolocator\ApiInfoLocator;
use Qween\Location\Component\Geolocator\ChainLocator;
use Qween\Location\Component\Geolocator\Ip;
use Qween\Location\Component\Geolocator\LocatorInterface;
use Qween\Location\Http\Response;

class IndexController extends AbstractController
{
    private LocatorInterface $locationService;

    public function __construct()
    {
        $this->locationService = new ChainLocator(new ApiInfoLocator(), new ApiGeoLocationLocator());
    }

    /**
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function index(): Response
    {
        $ip = new Ip($_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR']);

        $location = $this->locationService->locate($ip);

        return $this->render('home', [
            'title'   => 'Welcome to HOME',
            'ip'      => $ip->getValue(),
            'city'    => $location->getCity(),
            'country' => $location->getCountry(),
            'region'  => $location->getRegion()
        ]);
    }
}