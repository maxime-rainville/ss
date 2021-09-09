<?php
namespace MaximeRainville\SS\Env;

use MaximeRainville\SS\Env\EnvHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class BaseUrlHelper implements EnvHelper
{
    public function description(): string
    {
        return 'Base URL';
    }

    public function ask(Command $parent, InputInterface $input, ConsoleOutputInterface $output, string $default = null): array
    {
        $io = new SymfonyStyle($input, $output);

        if ($default === null) {
            $default = $this->defaultBaseUrl();
        }

        $domainQ = new Question('What is domain will be used to access your site?', $default);

        while (true) {
            $domain = $io->askQuestion($domainQ);
            if ($this->validDomain($domain)) {
                break;
            }
            $io->error(sprintf('%s is not a valid domain', $domain));
        }

        $protocolQ = new ChoiceQuestion(
            'What protocol will be used to access your site?',
            // choices can also be PHP objects that implement __toString() method
            ['http', 'https'],
            0
        );

        $protocol = $io->askQuestion($protocolQ);

        return [
            'SS_BASE_URL' => $protocol . '://' . $domain
        ];
    }

    /**
     * Return the default base Url based on the current folder name and the server hostname
     * @return string
     */
    public function defaultBaseUrl(): string
    {
        $dir =  basename(getcwd());
        $hostname = gethostname();
        return $dir . '.' . $hostname;
    }

    public function validDomain($domain): bool
    {
        return filter_var($domain, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME);
    }

}