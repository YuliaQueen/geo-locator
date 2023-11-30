<?php

use League\Container\Argument\Literal\ArrayArgument;
use League\Container\Argument\Literal\StringArgument;
use League\Container\Container;
use League\Container\ReflectionContainer;
use Qween\Location\Controller\AbstractController;
use Qween\Location\Event\EventDispatcher;
use Qween\Location\Http\Kernel;
use Qween\Location\Http\Middleware\ExtractRouteInfo;
use Qween\Location\Http\Middleware\RequestHandler;
use Qween\Location\Http\Middleware\RequestHandlerInterface;
use Qween\Location\Http\Middleware\RouterDispatch;
use Qween\Location\Routing\Router;
use Qween\Location\Routing\RouterInterface;
use Qween\Location\Template\TwigFactory;
use Twig\Environment;

// Application parameters
$basePath = dirname(__DIR__);
$routes = include BASE_PATH . '/routes/web.php';
$appEnv = $_ENV['APP_ENV'] ?? 'dev';
$viewsPath = BASE_PATH . '/views';

// Application services
$container = new Container();
$container->delegate(new ReflectionContainer(true));

$container->add('base-path', new StringArgument($basePath));
$container->add('APP_ENV', new StringArgument($appEnv));

// Routing services
$container->add(RouterInterface::class, Router::class);

$container->add(RequestHandlerInterface::class, RequestHandler::class)
    ->addArgument($container);

$container->add(Kernel::class)
    ->addArguments([
        $container,
        RequestHandlerInterface::class,
        EventDispatcher::class
    ]);

$container->add(RouterDispatch::class)
    ->addArguments([
        RouterInterface::class,
        $container
    ]);

$container->add(ExtractRouteInfo::class)
    ->addArgument(new ArrayArgument($routes));

// Twig services
$container->add('twig-factory', TwigFactory::class)
    ->addArguments([
        new StringArgument($viewsPath)
    ]);

$container->addShared('twig', function () use ($container): Environment {
    return $container->get('twig-factory')->create();
});

// Controller services
$container->inflector(AbstractController::class)
    ->invokeMethod('setContainer', [$container]);

return $container;
