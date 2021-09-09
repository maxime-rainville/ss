<?php
namespace MaximeRainville\SS\Host;

use http\Exception\InvalidArgumentException;
use Symfony\Component\Filesystem\Filesystem;

class HostFile
{
    /** @var string $path */
    private $path;

    /** @var Filesystem $fs */
    private $fs;

    /**
     * @param $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
        $this->fs = new Filesystem();

        if (!$this->fs->exists($path)) {
            throw new InvalidArgumentException("$path is not a valid file");
        }
    }

    /**
     * Check if there's an entry in the host file for the provided hostname
     * @param string $hostname
     * @return bool
     */
    public function contains(string $hostname): bool
    {
        $map = $this->getResolutions();
        return !empty($map[strtolower($hostname)]);
    }

    private function getResolutions(): array
    {
        $lines = $this->parse();
        $reso = [];
        foreach ($lines as $i => $line) {
            if (preg_match('/^(\S+)\s+(.+)$/', $line, $matches)) {
                $ip = $matches[1];
                $host = $matches[2];
                if (filter_var($ip, FILTER_VALIDATE_IP) && filter_var($host, FILTER_VALIDATE_DOMAIN)) {
                    $reso[strtolower($host)] = $ip;
                    continue;
                }
            }

            throw new \InvalidArgumentException("Line $i appears invalid: $line");
        }

        return $reso;
    }

    /**
     * Parse content of host file removing blank lines and content after #
     * @return string[]
     */
    private function parse(): array {
        $handle = fopen($this->path, "r");

        if (empty($handle)) {
            throw new \InvalidArgumentException("Could not read {$this->path}");
        }

        /** @var string[] $lines */
        $lines = [];
        $i = 0;

        try {
            while (($line = fgets($handle)) !== false) {
                $i++;
                $line = preg_replace('/#.*/', '', $line);
                $line = trim($line);
                if (!empty($line)) {
                    $lines[$i] = $line;
                }
            }

            return $lines;
        } finally {
            fclose($handle);
        }
    }

    public function add(string $ip, string $host)
    {
        $this->fs->appendToFile($this->path, "$ip\t$host");
    }

}
