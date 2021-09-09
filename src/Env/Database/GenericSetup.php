<?php

namespace MaximeRainville\SS\Env\Database;

use Exception;
use http\Exception\RuntimeException;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Tests\Question\StringChoice;

abstract class GenericSetup implements Setup
{

    public function getQuestions(array $defaults): array
    {
        $defaults = $this->mergeDefaults(array_filter($defaults));

        $server = new Question('Database server', $defaults['SS_DATABASE_SERVER'] ?: null);
        $server->setValidator(function ($answer) {
            if (empty(trim($answer))) {
                throw new RuntimeException('You must provide a database server');
            }
            return trim($answer);
        });

        $user = new Question('Database user', $defaults['SS_DATABASE_USERNAME'] ?: null);
        $user->setValidator(function ($answer) {
            if (empty(trim($answer))) {
                throw new RuntimeException('You must provide a database user');
            }
            return trim($answer);
        });

        $password = new Question('Database password');
        $password->isHidden(true);

        $port = new Question('Database port (blank to use default)');
        $port->setValidator(function ($answer) {
            $answer = trim($answer);
            if (empty($answer)) {
                return null;
            }

            $answer = intval($answer);
            if ($answer > 0) {
                throw new RuntimeException('Part must be an integer greater than zero');
            }

            return $answer;
        });

        $name = new Question('Database name', $defaults['SS_DATABASE_NAME'] ?: null);

        return [
            'SS_DATABASE_SERVER' => $server,
            'SS_DATABASE_USERNAME' => $user,
            'SS_DATABASE_PASSWORD' => $password,
            'SS_DATABASE_PORT' => $port,
            'SS_DATABASE_NAME' => $name
        ];


    }

    /**
     * Merge the provided defaults with the adapter defaults
     * @param array $defaults
     * @return array
     */
    protected abstract function mergeDefaults(array $defaults): array;

}