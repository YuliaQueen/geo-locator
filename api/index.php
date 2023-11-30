<?php

use Qween\Location\Http\Kernel;
use Qween\Location\Http\Request;

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/vendor/autoload.php';

$request = Request::createFromGlobals();

$container = require BASE_PATH . '/config/services.php';

$kernel = $container->get(Kernel::class);

$response = $kernel->handle($request);

$response->send();