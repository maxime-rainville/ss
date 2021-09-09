<?php
namespace MaximeRainville\SS\Env;

use MaximeRainville\SS\Env\Database\Mysql;
use MaximeRainville\SS\Env\Database\MysqlPdo;
use MaximeRainville\SS\Env\Database\Postgresql;
use MaximeRainville\SS\Env\Database\Setup;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

class DatabaseHelper implements EnvHelper
{
    public function description(): string
    {
        return 'Database set up';
    }

    public function ask(Command $parent, InputInterface $input, ConsoleOutputInterface $output, string $default = null): array
    {
        $io = new SymfonyStyle($input, $output);

        $values = [];
        $setups = $this->getSetUps();
        $dbClassOptions = [];
        foreach ($setups as $key => $setup) {
            $dbClassOptions[$key] = $setup->getDatabaseTitle();
        }

        $dbClassTitle = $io->askQuestion(new ChoiceQuestion(
            'What Database adapter do you want to use?',
            $dbClassOptions,
            0
        ));

        foreach ($setups as $key => $setup) {
            if ($setup->getDatabaseTitle() === $dbClassTitle) {
                break;
            }
        }
        $values['SS_DATABASE_CLASS'] = $setup->getDatabaseClass();

        foreach ($setup->getQuestions([]) as $key => $question) {
            $values[$key] = $io->askQuestion($question);
        }

        return $values;

//        SS_SQLITE_DATABASE_PATH

    }

    /**
     * @return Setup[]
     */
    private function getSetUps(): array
    {
        return [
            new Mysql(),
            new MysqlPdo(),
            new Postgresql()
        ];
    }

}