<?php

namespace Druidfi\ComposerSlimmer;

use Composer\IO\IOInterface;
use Composer\Package\Package;

class Cleaner
{
    use UtilsTrait;

    private string $packagePath;

    public function __construct(IOInterface $io)
    {
        $this->io = $io;
    }

    public function clean(Package $package, string $packagePath): int
    {
        $this->packagePath = $packagePath;
        $extra = [];

        if (str_contains($package->getPrettyName(), 'drupal/')) {
            $this->io->write('<info>Loading Drupal package rules...</info>');
            $drupalRules = require __DIR__ . '/../data/drupal.php' ?? [];
            $extra = $drupalRules[$package->getPrettyName()] ?? [];
        }

        $recursiveCleaner = new RecursiveCleaner($this->io);
        $totalSize = $recursiveCleaner->clean($packagePath, $extra);

        return $totalSize;
    }
}
