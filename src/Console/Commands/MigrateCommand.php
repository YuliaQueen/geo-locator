<?php

namespace Qween\Location\Console\Commands;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Types\Types;
use Qween\Location\Console\CommandInterface;

class MigrateCommand implements CommandInterface
{
    private string $name = 'migrate';
    private const MIGRATIONS_TABLE = 'migrations';

    public function __construct(
        private Connection $connection
    )
    {
    }

    /**
     * @throws SchemaException
     * @throws Exception
     */
    public function execute($parameters = []): int
    {
        $this->createMigrationsTable();
        return 0;
    }

    /**
     * @throws SchemaException
     * @throws Exception
     */
    private function createMigrationsTable(): void
    {
        $schemaManager = $this->connection->createSchemaManager();

        if ($schemaManager->tablesExist([self::MIGRATIONS_TABLE])) {
            return;
        }

        $schema = new Schema();
        $table = $schema->createTable('migrations');

        $table->addColumn('id', Types::INTEGER, [
            'autoincrement' => true,
            'unsigned' => true
        ]);

        $table->addColumn('migration', Types::STRING, [
            'length' => 255
        ]);

        $table->addColumn('created_at', Types::DATETIME_MUTABLE, [
            'notnull' => true,
            'default' => 'CURRENT_TIMESTAMP'
        ]);

        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['migration']);

        $schemaManager->createTable($table);
    }
}