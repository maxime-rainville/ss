<?php


namespace MaximeRainville\SS\Env\Database;


/**
 * Set up for a generic MySQL Database connection
 */
class Mysql extends GenericSetup
{
    protected function mergeDefaults(array $defaults): array
    {
        return array_merge(
            [
                'SS_DATABASE_SERVER' => 'localhost',
                'SS_DATABASE_USERNAME' => 'root',
                'SS_DATABASE_NAME' => 'SS_' . basename(getcwd())
            ],
            $defaults
        );
    }

    public function getDatabaseClass(): string
    {
        return 'MySQLDatabase';
    }

    public function getDatabaseTitle(): string
    {
        return 'MySQL/MariaDB';
    }

    public function getPackageName(): ?string
    {
        return null;
    }

    public function testConnection(array $config): bool
    {
        return true;
    }
}