<?php

namespace Qween\Location\Console;

interface CommandInterface
{
    public function execute($parameters = []): int;
}