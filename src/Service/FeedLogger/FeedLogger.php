<?php

namespace App\Service\FeedLogger;

use Symfony\Component\Filesystem\Filesystem;

class FeedLogger
{

    const SEPARATOR = ',';
    const LOG_FILE = 'logs/feed.log';

    public function __construct(private readonly Filesystem $filesystem)
    {
    }

    public function log(string $feedId): void
    {
        if (!$this->filesystem->exists(self::LOG_FILE)) {
            $this->filesystem->dumpFile(self::LOG_FILE, $feedId . self::SEPARATOR);
        }
        else {
            $this->filesystem->appendToFile(self::LOG_FILE, $feedId . self::SEPARATOR);
        }
    }

    public function getLogAsArray(): array
    {
        return explode(',', $this->read());
    }

    private function read(): string
    {
        if ($this->filesystem->exists(self::LOG_FILE)) {
            $file = file_get_contents(self::LOG_FILE);
        }

        return $file ?? '';
    }

}