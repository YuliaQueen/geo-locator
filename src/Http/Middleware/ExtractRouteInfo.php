<?php

namespace Qween\Location\Http\Middleware;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Qween\Location\Http\Exceptions\MethodNotAllowedException;
use Qween\Location\Http\Exceptions\RouteNotFoundException;
use Qween\Location\Http\Request;
use Qween\Location\Http\Response;
use function FastRoute\simpleDispatcher;

class ExtractRouteInfo implements MiddlewareInterface
{
    const STATUS_NOT_ALLOWED = 405;
    const STATUS_NOT_FOUND   = 404;

    public function __construct(
        private array $routes
    )
    {
    }

    /**
     * @throws MethodNotAllowedException
     * @throws RouteNotFoundException
     */
    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $collector) {
            foreach ($this->routes as $route) {
                $collector->addRoute(...$route);
            }
        });

        $routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getPath());

        switch ($routeInfo[0]) {
            case Dispatcher::FOUND:
                $request->setRouteHandler($routeInfo[1][0]);
                $request->setRouteArgs($routeInfo[2]);
                $handler->injectMiddleware($routeInfo[1][1]);
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = implode(', ', $routeInfo[1]);
                $message = 'Supported methods: ' . $allowedMethods;
                $exception = new MethodNotAllowedException($message);
                $exception->setStatusCode(self::STATUS_NOT_ALLOWED);
                throw $exception;
            default:
                $exception = new RouteNotFoundException('Route not found');
                $exception->setStatusCode(self::STATUS_NOT_FOUND);
                throw $exception;
        }

        return $handler->handle($request);
    }
}