<?php

namespace Qween\Location\Http\Middleware;

use Qween\Location\Http\Request;
use Qween\Location\Http\Response;

interface MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $handler): Response;
}