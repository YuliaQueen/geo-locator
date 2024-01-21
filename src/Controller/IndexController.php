<?php

namespace Qween\Location\Controller;

use Doctrine\DBAL\Exception;
use Monolog\Logger;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Qween\Location\Component\Geolocator\ApiGeoLocationLocator;
use Qween\Location\Component\Geolocator\ApiInfoLocator;
use Qween\Location\Component\Geolocator\ChainLocator;
use Qween\Location\Component\Geolocator\ErrorHandler;
use Qween\Location\Component\Geolocator\Ip;
use Qween\Location\Component\Geolocator\LocatorInterface;
use Qween\Location\Component\Geolocator\MuteLocator;
use Qween\Location\Dbal\Models\Location;
use Qween\Location\Http\Response;
use Qween\Location\Service\LocationService;

class IndexController extends AbstractController
{
    private LocatorInterface $locator;
    private ErrorHandler $handler;
    private $service;

    public function __construct(
        LocationService $service
    )
    {
        $this->handler = new ErrorHandler(new Logger('location'));
        $this->locator = new ChainLocator(new MuteLocator(
            new ApiInfoLocator(),
            $this->handler
        ), new MuteLocator(
            new ApiGeoLocationLocator(),
            $this->handler
        ));
        $this->service = $service;
    }

    /**
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function index(): Response
    {
        $ip = new Ip($_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR']);

        $location = $this->service->findByIp($ip->getValue());

        if (!$location) {
            $location = $this->locator->locate($ip);

            $model = new Location();
            $model->setId($ip->getValue());
            $model->setCountry($location->getCountry());
            $model->setRegion($location->getRegion());
            $model->setCity($location->getCity());

            $this->service->save($model);
        }

        return $this->render('home', [
            'title'   => 'Welcome to HOME',
            'ip'      => $ip->getValue(),
            'city'    => $location->getCity(),
            'country' => $location->getCountry(),
            'region'  => $location->getRegion()
        ]);
    }
}