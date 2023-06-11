<?php

namespace Druidfi\ComposerSlimmer;

use Composer\IO\IOInterface;
use Composer\Package\Package;

class Cleaner
{
    use UtilsTrait;

    private string $packagePath;

    //private array $files = [];
    //private array $folders = [];
    //private array $packages = [];

    public function __construct(IOInterface $io)
    {
        $this->io = $io;
    }

    public function clean(Package $package, string $packagePath): int
    {
        $this->packagePath = $packagePath;

        /*if (isset($this->packages[$package->getPrettyName()])) {
            if (isset($this->packages[$package->getPrettyName()]['files'])) {
                $this->files = array_merge($this->files, $this->packages[$package->getPrettyName()]['files'] ?? []);
            }

            if (isset($this->packages[$package->getPrettyName()]['folders'])) {
                $this->folders = array_merge($this->folders, $this->packages[$package->getPrettyName()]['folders'] ?? []);
            }

            unset($this->packages[$package->getPrettyName()]);
        }*/

        $recursiveCleaner = new RecursiveCleaner($this->io);
        $totalSize = $recursiveCleaner->clean($packagePath);

        return $totalSize;
    }
}
