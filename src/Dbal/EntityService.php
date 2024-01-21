<?php

namespace Qween\Location\Dbal;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class EntityService
{
    public function __construct(
        private Connection $connection
    )
    {
    }

    public function getConnection(): Connection
    {
        return $this->connection;
    }

    /**
     * @throws Exception
     */
    public function save(Entity $entity): int
    {
        $lastInsertId = $this->connection->lastInsertId();
        $entity->setId($lastInsertId);

        return $lastInsertId;
    }
}