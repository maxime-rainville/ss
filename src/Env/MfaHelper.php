<?php
namespace MaximeRainville\SS\Env;

use MaximeRainville\SS\Env\EnvHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Ask the user if they want to enter an MFA secret and offer to generate a random one.
 */
class MfaHelper implements EnvHelper
{
    public function description(): string
    {
        return 'MFA key';
    }

    public function ask(Command $parent, InputInterface $input, ConsoleOutputInterface $output, string $default = null): array
    {
        $io = new SymfonyStyle($input, $output);

        $mfaQuestion = new ChoiceQuestion(
            'Do yu want an MFA key?',
            ['Generate a random one', 'Enter one manually', 'No key'],
            0
        );

        $mfaChoice = $io->askQuestion($mfaQuestion);

        if ($mfaChoice === 'No key') {
            return [];
        }

        if ($mfaChoice === 'Generate a random one') {
            $key = substr(base64_encode(random_bytes(32)), 0, 32);
        } else {
            $question = new Question('Enter your MFA key');
            $key = $io->askQuestion($question);
        }

        return [
            'SS_MFA_SECRET_KEY' => $key
        ];
    }

}