<?php

namespace Qween\Location\Http\Middleware;

use Psr\Container\ContainerInterface;
use Qween\Location\Http\Request;
use Qween\Location\Http\Response;
use Qween\Location\Routing\RouterInterface;

class RouterDispatch implements MiddlewareInterface
{
    public function __construct(
        private RouterInterface    $router,
        private ContainerInterface $container
    )
    {
    }

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        [$routerHandler, $vars] = $this->router->dispatch($request, $this->container);
        return call_user_func_array($routerHandler, $vars);
    }
}