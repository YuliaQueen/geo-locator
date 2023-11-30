<?php

namespace Qween\Location\Routing;

use Psr\Container\ContainerInterface;
use Qween\Location\Http\Request;

interface RouterInterface
{
    public function dispatch(Request $request, ContainerInterface $container): array;
}