<?php
namespace MaximeRainville\SS\Host;

use http\Exception\RuntimeException;
use M1\Env\Parser;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class Command extends SymfonyCommand
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'host';

    protected function configure(): void
    {
        $this
            ->setDescription('Update the host file')
            ->setHelp('Add the SS_BASE_URL to the system host file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if (!file_exists('.env')) {
            $io->error("Could not find an environment file. Run `ss-cli env` first");
            return Command::INVALID;
        }

        $envVars = Parser::parse(file_get_contents('.env'));
        if (empty($envVars['SS_BASE_URL'])) {
            $io->error("There's no SS_BASE_URL in your host file");
            return Command::INVALID;
        }

        $baseUrl = strtolower($envVars['SS_BASE_URL']);
        $hostname = parse_url($baseUrl,  PHP_URL_HOST);

        $hostFile = new HostFile('/etc/hosts');

        if ($hostFile->contains($hostname)) {
            $io->success("$hostname is already in the host file");
        } elseif (empty(getenv('SUDO_COMMAND'))) {
            $io->warning("$hostname is not in your host file. Run this command with sudo to add it.");
        } elseif ($io->confirm("$hostname is not in your host file. Do you want to add it?")) {
            $ipQ = new Question('What IP do you want to use', "127.0.0.1");
            $ipQ->setValidator(function ($answer) {
                if (!filter_var($answer, FILTER_VALIDATE_IP)) {
                    throw new \RuntimeException('You must provide a valid IP');
                }
                return trim($answer);
            });

            $ip = $io->askQuestion($ipQ);
            $hostFile->add($ip, $hostname);
        }
        return Command::SUCCESS;
    }

    public function toEnvString(array $keys): string
    {
        $str = '';
        foreach ($keys as $section => $values) {
            $str .= "# $section\n";
            foreach ($values as $key => $value) {
                if ($value !== null) {
                    $str .= sprintf("%s=\"%s\"\n", $key, $value);
                }
            }
            $str .= "\n";
        }
        return $str;
    }

    /**
     * @return EnvHelper[]
     */
    public function defaultHelpers(): array
    {
        return [
            new BaseUrlHelper(),
            new EnvTypeHelper(),
            new DatabaseHelper(),
            new DefaultAdminHelper()
        ];
    }
}
