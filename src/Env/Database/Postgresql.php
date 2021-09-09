<?php


namespace MaximeRainville\SS\Env\Database;


/**
 * Set up for a generic MySQL Database connection
 */
class Postgresql extends GenericSetup
{
    protected function mergeDefaults(array $defaults): array
    {
        return array_merge(
            [
                'SS_DATABASE_SERVER' => 'localhost',
                'SS_DATABASE_USERNAME' => 'postgres',
                'SS_DATABASE_NAME' => 'SS_' . basename(getcwd())
            ],
            $defaults
        );
    }

    public function getDatabaseClass(): string
    {
        return 'PostgreSQLDatabase';
    }

    public function getDatabaseTitle(): string
    {
        return 'PostgreSQL';
    }

    public function getPackageName(): ?string
    {
        return 'silverstripe/postgresql';
    }

    public function testConnection(array $config): bool
    {
        return true;
    }
}