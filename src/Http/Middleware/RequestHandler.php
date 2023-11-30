<?php

namespace Qween\Location\Http\Middleware;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Qween\Location\Http\Request;
use Qween\Location\Http\Response;

class RequestHandler implements RequestHandlerInterface
{
    private array $middlewares = [
        ExtractRouteInfo::class,
        RouterDispatch::class
    ];

    public function __construct(
        private ContainerInterface $container
    )
    {

    }

    /**
     * @param Request $request
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handle(Request $request): Response
    {
        if (empty($this->middlewares)) {
            return new Response('Internal server error', 500);
        }

        $middlewareClass = array_shift($this->middlewares);

        $middleware = $this->container->get($middlewareClass);

        return $middleware->process($request, $this);
    }

    public function injectMiddleware(array $middlewares): void
    {
        array_splice($this->middlewares, 0, 0, $middlewares);
    }
}