<?php

namespace Druidfi\ComposerSlimmer;

use Composer\IO\IOInterface;
use Composer\Package\Package;

class Cleaner
{
    use UtilsTrait;

    private string $packagePath;

    private array $extras = [];

    public function __construct(IOInterface $io)
    {
        $this->io = $io;
    }

    public function clean(Package $package, string $packagePath): int
    {
        $this->packagePath = $packagePath;
        $extra = [];

        if (str_contains($package->getPrettyName(), 'drupal/')) {
            if (empty($this->extras['drupal'])) {
                $this->io->write('<info>Loading Drupal package rules...</info>');
                $this->extras['drupal'] = require __DIR__ . '/../data/drupal.php' ?? [];
            }

            $extra = $this->extras['drupal'][$package->getPrettyName()] ?? [];
        }

        $recursiveCleaner = new RecursiveCleaner($this->io);

        return $recursiveCleaner->clean($packagePath, $extra);
    }
}
