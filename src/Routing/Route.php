<?php

namespace Qween\Location\Routing;

class Route
{
    /**
     * @param string         $uri
     * @param array|callable $handler
     * @param array          $middleware
     * @return array
     */
    public static function get(string $uri, array|callable $handler, array $middleware = []): array
    {
        return ['GET', $uri, [$handler, $middleware]];
    }

    /**
     * @param string         $uri
     * @param array|callable $handler
     * @param array          $middleware
     * @return array
     */
    public static function post(string $uri, array|callable $handler, array $middleware = []): array
    {
        return ['POST', $uri, [$handler, $middleware]];
    }
}