<?php

namespace Druidfi\DrupalSlimmer;

use Composer\IO\IOInterface;
use Composer\Package\Package;
use Composer\Util\Filesystem;

class Cleaner
{
    private IOInterface $io;
    private Filesystem $filesystem;
    private string $packagePath;

    public function __construct(IOInterface $io)
    {
        $this->io = $io;
        $this->filesystem = new Filesystem();
    }

    public function clean(Package $package, string $packagePath)
    {
        $this->packagePath = $packagePath;

        $this->io->write('<info>Clean up on ' . $package->getName() . '</info>');

        $this->removeFile('README.md');
        $this->removeFolder('tests');
    }

    private function removeFile(string $fileName)
    {
        $file = $this->packagePath . DIRECTORY_SEPARATOR . $fileName;

        if (file_exists($file)) {
            $size = $this->size($this->filesystem->size($file));
            $this->write('Removing file', $file .' ('. $size .')');
            $this->filesystem->remove($file);
        }
    }

    private function removeFolder(string $folderName)
    {
        $folder = $this->packagePath . DIRECTORY_SEPARATOR . $folderName;

        if (file_exists($folder)) {
            $size = $this->size($this->filesystem->size($folder));
            $this->write('Removing folder', $folder . ' (' . $size . ')');
            $this->filesystem->removeDirectory($folder);
        }
    }

    private function write(string $action, string $target): void
    {
        $message = sprintf('  - %s <comment>%s</comment>', $action, $this->nice($target));

        $this->io->write($message);
    }

    private function nice(string $path): string
    {
        return str_replace(getcwd() . DIRECTORY_SEPARATOR, '', $this->filesystem->normalizePath($path));
    }

    private function size(int $bytes, int $dec = 2): string
    {
        $size = ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $factor = floor((strlen($bytes) - 1) / 3);
        if ($factor == 0) $dec = 0;

        return sprintf("%.{$dec}f %s", $bytes / (1024 ** $factor), $size[$factor]);
    }
}
