<?php

namespace Qween\Location\Console\Commands;

use Qween\Location\Console\CommandInterface;

class MigrateCommand implements CommandInterface
{
    private string $name = 'migrate';

    public function execute($parameters = []): int
    {
        return 0;
    }
}