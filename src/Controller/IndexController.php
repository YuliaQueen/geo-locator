<?php

namespace Qween\Location\Controller;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Qween\Location\Http\Response;
use Qween\Location\Component\Geolocator\Locator;

class IndexController extends AbstractController
{

    /**
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function index(): Response
    {
        $locator = new Locator();

        $ip = $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];

        $location = $locator->locate($ip);
        return $this->render('home', [
            'title'   => 'Welcome to HOME',
            'ip'      => $ip,
            'city'    => $location->getCity(),
            'country' => $location->getCountry(),
            'region'  => $location->getRegion()
        ]);
    }
}