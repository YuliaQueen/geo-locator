<?php

namespace Qween\Location\Console\Commands;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Types\Types;
use Qween\Location\Console\CommandInterface;

class MigrateCommand implements CommandInterface
{
    private string $name = 'migrate';
    private const MIGRATIONS_TABLE = 'migrations';

    public function __construct(
        private Connection $connection,
        private string     $migrationsPath
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
        $appliedMigrations = $this->getAppliedMigrations();
        $migrationFiles = $this->getMigrationFiles();

        $migrationToApply = array_diff($migrationFiles, $appliedMigrations);

        if (empty($migrationToApply)) {
            echo "\033[32mAll migrations are applied\033[0m";
            return 0;
        }

        $schema = new Schema();

        foreach ($migrationToApply as $migrationFile) {
            $migration = require $this->migrationsPath . '/' . $migrationFile;
            $migration->up($schema);
            $this->addMigrationToTable($migrationFile);
        }

        $sqlArray = $schema->toSql($this->connection->getDatabasePlatform());
        foreach ($sqlArray as $sql) {
            $this->connection->executeStatement($sql);
        }

        return 0;
    }

    /**
     * @throws SchemaException
     * @throws Exception
     */
    private function createMigrationsTable(): void
    {
        $schemaManager = $this->getSchemaManager();

        if ($schemaManager->tablesExist([self::MIGRATIONS_TABLE])) {
            return;
        }

        $schema = new Schema();
        $table = $schema->createTable('migrations');

        $table->addColumn('id', Types::INTEGER, [
            'autoincrement' => true,
            'unsigned'      => true
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

    /**
     * @throws Exception
     */
    private function getAppliedMigrations(): array
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        return $queryBuilder
            ->select('migration')
            ->from(self::MIGRATIONS_TABLE)
            ->executeQuery()
            ->fetchFirstColumn();
    }

    private function getMigrationFiles(): array
    {
        $files = [];
        $directoryIterator = new \DirectoryIterator($this->migrationsPath);

        foreach ($directoryIterator as $fileInfo) {
            if ($fileInfo->isDot()) {
                continue;
            }
            $files[] = $fileInfo->getFilename();
        }

        return $files;
    }

    /**
     * @param mixed $migrationFile
     * @return void
     * @throws Exception
     */
    private function addMigrationToTable(mixed $migrationFile): void
    {
        $this->connection->insert(self::MIGRATIONS_TABLE, [
            'migration' => $migrationFile
        ]);
    }

    /**
     * @return AbstractSchemaManager
     * @throws Exception
     */
    private function getSchemaManager(): AbstractSchemaManager
    {
        return $this->connection->createSchemaManager();
    }
}