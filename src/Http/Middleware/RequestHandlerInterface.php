<?php

namespace Qween\Location\Http\Middleware;

use Qween\Location\Http\Request;
use Qween\Location\Http\Response;

interface RequestHandlerInterface
{
    public function handle(Request $request): Response;

    public function injectMiddleware(array $middlewares): void;
}