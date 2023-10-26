<?php

namespace App\Service\FeedLogger;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

class FeedLogger
{

    const SEPARATOR = ',';
    const LOG_FILE = '/logs/feed.log';

    private string $projectDir;

    public function __construct(
        private readonly Filesystem $filesystem,
        private readonly KernelInterface $appKernel
    )
    {
        $this->projectDir = $this->appKernel->getProjectDir();
    }

    public function log(string $feedId): void
    {
        if (!$this->filesystem->exists($this->projectDir . self::LOG_FILE)) {
            $this->filesystem->dumpFile($this->projectDir . self::LOG_FILE, $feedId . self::SEPARATOR);
        }
        else {
            $this->filesystem->appendToFile($this->projectDir . self::LOG_FILE, $feedId . self::SEPARATOR);
        }
    }

    public function getLogAsArray(): array
    {
        return explode(',', $this->read());
    }

    private function read(): string
    {
        if ($this->filesystem->exists($this->projectDir . self::LOG_FILE)) {
            $file = file_get_contents($this->projectDir . self::LOG_FILE);
        }

        return $file ?? '';
    }

}