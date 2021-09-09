<?php
namespace MaximeRainville\SS\Env;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;

/**
 * Helper that can be used to ask the user for a value for a specific key
 */
interface EnvHelper
{
    /**
     * Description for this Environment key
     */
    public function description(): string;

    /**
     * Get the helper to ask for a key from the user
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param string|null $default
     */
    public function ask(
        Command $parent,
        InputInterface $input,
        ConsoleOutputInterface $output,
        string $default = null
    ): array;

}