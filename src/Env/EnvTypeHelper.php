<?php
namespace MaximeRainville\SS\Env;

use MaximeRainville\SS\Env\EnvHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class EnvTypeHelper implements EnvHelper
{
    public function description(): string
    {
        return 'Environment type';
    }

    public function ask(Command $parent, InputInterface $input, ConsoleOutputInterface $output, string $default = null): array
    {
        $io = new SymfonyStyle($input, $output);

        $envs = [
            'dev',
            'test',
            'live'
        ];

        if ($default === null) {
            $default = 'dev';
        }

        $idx = array_search($default, $envs);
        if ($idx === false) {
            $idx = 0;
        }

        $envQ = new ChoiceQuestion('What\'s the Environment type', $envs, $idx);

        return [
            'SS_ENVIRONMENT_TYPE' => $io->askQuestion($envQ)
        ];
    }
}