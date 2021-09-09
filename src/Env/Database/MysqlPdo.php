<?php


namespace MaximeRainville\SS\Env\Database;


/**
 * Set up for a generic MySQL Database connection
 */
class MysqlPdo extends Mysql
{
    public function getDatabaseClass(): string
    {
        return 'MySQLPDODatabase';
    }

    public function getDatabaseTitle(): string
    {
        return 'MySQL/MariaDB PDO';
    }
}