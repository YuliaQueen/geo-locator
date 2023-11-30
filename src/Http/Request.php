<?php

namespace Qween\Location\Http;

class Request
{
    private mixed $routeHandler;

    private array $routeArgs;

    /**
     * @param array $getParams
     * @param array $postData
     * @param array $files
     * @param array $server
     * @param array $cookies
     */
    public function __construct(
        private readonly array $getParams,
        private readonly array $postData,
        private readonly array $files,
        private readonly array $server,
        private readonly array $cookies
    )
    {
    }

    /**
     * @return static
     */
    public static function createFromGlobals(): static
    {
        return new static($_GET, $_POST, $_FILES, $_SERVER, $_COOKIE);
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->server['REQUEST_METHOD'];
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return strtok($this->server['REQUEST_URI'], '?');
    }

    /**
     * @return array
     */
    public function getGetParams(): array
    {
        return $this->getParams;
    }

    /**
     * @return array
     */
    public function getPostData(): array
    {
        return $this->postData;
    }

    /**
     * @return array
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * @return array
     */
    public function getServer(): array
    {
        return $this->server;
    }

    /**
     * @return array
     */
    public function getCookies(): array
    {
        return $this->cookies;
    }

    public function input(string $key, $default = null): mixed
    {
        return $this->postData[$key] ?? $default;
    }

    public function setRouteHandler(mixed $handler): void
    {
        $this->routeHandler = $handler;
    }

    public function setRouteArgs(array $args): void
    {
        $this->routeArgs = $args;
    }

    public function getRouteHandler(): mixed
    {
        return $this->routeHandler;
    }

    public function getRouteArgs(): array
    {
        return $this->routeArgs;
    }
}