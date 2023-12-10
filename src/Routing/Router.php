<?php

namespace Qween\Location\Routing;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Qween\Location\Controller\AbstractController;
use Qween\Location\Http\Request;

class Router implements RouterInterface
{
    /**
     * @param Request            $request
     * @param ContainerInterface $container
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function dispatch(Request $request, ContainerInterface $container): array
    {
        $handler = $request->getRouteHandler();
        $vars = $request->getRouteArgs();
        if (is_array($handler)) {
            [$controllerId, $action] = $handler;
            $controller = $container->get($controllerId);
            if (is_subclass_of($controller, AbstractController::class)) {
                $controller->setRequest($request);
            }

            $handler = [$controller, $action];
        }

        return [$handler, $vars];
    }
}
