<?php

use Doctrine\DBAL\Connection;
use GuzzleHttp\Client;
use League\Container\Argument\Literal\ArrayArgument;
use League\Container\Argument\Literal\StringArgument;
use League\Container\Container;
use League\Container\ReflectionContainer;
use Psr\Http\Client\ClientInterface;
use Qween\Location\Component\Geolocator\Locator;
use Qween\Location\Component\Geolocator\LocatorInterface;
use Qween\Location\Console\Application;
use Qween\Location\Console\CommandPrefix;
use Qween\Location\Console\Commands\MigrateCommand;
use Qween\Location\Controller\AbstractController;
use Qween\Location\Dbal\ConnectionFactory;
use Qween\Location\Event\EventDispatcher;
use Qween\Location\Http\Kernel;
use Qween\Location\Console\Kernel as ConsoleKernel;
use Qween\Location\Http\Middleware\ExtractRouteInfo;
use Qween\Location\Http\Middleware\RequestHandler;
use Qween\Location\Http\Middleware\RequestHandlerInterface;
use Qween\Location\Http\Middleware\RouterDispatch;
use Qween\Location\Routing\Router;
use Qween\Location\Routing\RouterInterface;
use Qween\Location\Template\TwigFactory;
use Symfony\Component\Dotenv\Dotenv;
use Twig\Environment;

$dotenv = new Dotenv();
$dotenv->load(dirname(__DIR__) . '/.env');

// Application parameters
$basePath = dirname(__DIR__);
$routes = include BASE_PATH . '/routes/web.php';
$appEnv = $_ENV['APP_ENV'] ?? 'dev';
$viewsPath = BASE_PATH . '/views';
$databaseUrl = $_ENV['DATABASE_URL'] ?? 'pdo-sqlite:///location.db';

// Application services
$container = new Container();
$container->delegate(new ReflectionContainer(true));

$container->add('base-path', new StringArgument($basePath));
$container->add('APP_ENV', new StringArgument($appEnv));

// HTTP services
$container->add(ClientInterface::class, Client::class);
$container->add(LocatorInterface::class, Locator::class)
    ->addArgument(ClientInterface::class);

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

// Database services
$container->add(ConnectionFactory::class)
    ->addArgument(new StringArgument($databaseUrl));

$container->addShared(Connection::class, function () use ($container): Connection {
    return $container->get(ConnectionFactory::class)->create();
});

// Console services
$container->add(Application::class)
    ->addArgument($container);

$container->add(ConsoleKernel::class)
    ->addArguments([
        $container,
        Application::class
    ]);

$container->add(CommandPrefix::CONSOLE->value . 'migrate', MigrateCommand::class)
    ->addArgument(Connection::class)
    ->addArgument(new StringArgument(BASE_PATH . '/database/migrations'));

$container->add('console-command-namespace', new StringArgument('Qween\\Location\\Console\\Commands\\'));

return $container;
