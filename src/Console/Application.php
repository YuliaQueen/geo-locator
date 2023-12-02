<?php

namespace Qween\Location\Console;

use Psr\Container\ContainerInterface;
use Qween\Location\Console\Exceptions\ConsoleException;

class Application
{
    public function __construct(
        private ContainerInterface $container
    )
    {
    }

    public function run(): int
    {
        try {
            $argv = $_SERVER['argv'];

            $commandName = $argv[1] ?? throw new ConsoleException('Command name is required');
            $command = $this->container->get(CommandPrefix::CONSOLE->value . $commandName);
            $parameters = array_slice($argv, 2);

            if (!empty($parameters)) {
                $parameters = $this->parseParameters($parameters);
            }
            return $command->execute($parameters);
        } catch (ConsoleException $exception) {
            $message = $exception->getMessage();
            echo "\033[31m{$message}\033[0m";
        }

        return 0;
    }

    private function parseParameters(array $argv): array
    {
        $parameters = [];

        foreach ($argv as $arg) {
            if (!str_starts_with($arg, '--' ?? '-') && !str_contains($arg, '=')) {
                continue;
            }

            [$key, $value] = explode('=', $arg, 2);
            $parameters[ltrim($key, '--' ?? '-')] = $value ?? true;
        }

        return $parameters;
    }
}