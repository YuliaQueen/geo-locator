<?php

namespace Qween\Location\Console;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class Kernel
{
    public function __construct(
        private ContainerInterface $container,
        private Application        $application
    )
    {
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws \ReflectionException
     * @throws NotFoundExceptionInterface
     */
    public function handle(): int
    {
        $this->registerCommands();
        return $this->application->run();
    }

    /**
     * @throws \ReflectionException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function registerCommands(): void
    {
        $commandFiles = new \DirectoryIterator(__DIR__ . '/Commands');
        $namespace = $this->container->get('console-command-namespace');

        foreach ($commandFiles as $file) {
            if (!$file->isFile() || $file->isDot()) {
                continue;
            }

            $command = $namespace . $file->getBasename('.php');

            if (is_subclass_of($command, CommandInterface::class)) {
                $name = (new \ReflectionClass($command))->getProperty('name')->getDefaultValue();
                $key = CommandPrefix::CONSOLE->value . $name;
                $this->container->add($key, $command);
            }
        }
    }
}