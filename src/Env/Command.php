<?php
namespace MaximeRainville\SS\Env;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Command extends SymfonyCommand
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'env';

    protected function configure(): void
    {
        $this
            ->setDescription('Set up Silverstripe CMS .env file')
            ->setHelp('Allows you to set up a .env file for your Silverstripe CMS');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helpers = $this->defaultHelpers();
        $io = new SymfonyStyle($input, $output);

        $keys = [];

        foreach ($helpers as $helper) {
            $io->title($helper->description());
            $keys[$helper->description()] = $helper->ask($this, $input, $output);
        }

        $dotEnvContent = $this->toEnvString($keys);
        $io->title('Your .env file content');
        $io->block($dotEnvContent);

        if ($io->confirm('Write this content to your .env file?')) {
            file_put_contents('.env', $dotEnvContent);
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
            new DefaultAdminHelper(),
            new MfaHelper(),
        ];
    }
}
