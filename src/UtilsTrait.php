<?php

namespace Druidfi\ComposerSlimmer;

use Composer\IO\IOInterface;

trait UtilsTrait
{
    private ?IOInterface $io;

    private function write(string $action, string $target, int $size): void
    {
        $message = sprintf('  - %s <comment>%s</comment> (%s)', $action, $this->nice($target), $this->size($size));

        $this->io->write($message);
    }

    private function nice(string $path): string
    {
        return str_replace(getcwd() . DIRECTORY_SEPARATOR, '', $this->filesystem->normalizePath($path));
    }

    public static function size(int $bytes, int $dec = 2): string
    {
        $size = ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $factor = floor((strlen($bytes) - 1) / 3);
        if ($factor == 0) $dec = 0;

        return sprintf("%.{$dec}f %s", $bytes / (1024 ** $factor), $size[$factor]);
    }
}
