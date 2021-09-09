<?php
namespace MaximeRainville\SS\Env;

use MaximeRainville\SS\Env\EnvHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class DefaultAdminHelper implements EnvHelper
{
    public function description(): string
    {
        return 'Default administrator';
    }

    public function ask(Command $parent, InputInterface $input, ConsoleOutputInterface $output, string $default = null): array
    {
        $io = new SymfonyStyle($input, $output);

        if ($default === null) {
            $default = 'admin';
        }

        $username = new Question('What should be the default admin username', $default);
        $password = new Question('What should be the default admin password', 'admin');

        return [
            'SS_DEFAULT_ADMIN_USERNAME' => $io->askQuestion($username),
            'SS_DEFAULT_ADMIN_PASSWORD' => $io->askQuestion($password)
        ];
    }
}