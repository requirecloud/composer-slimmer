<?php

namespace Druidfi\DrupalSlimmer;

use Composer\IO\IOInterface;
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

    public function clean(string $packagePath)
    {
        $this->packagePath = $packagePath;

        $this->io->write('<info>cleanUp on ' . $packagePath . '</info>');

        $this->removeFile('README.md');
        $this->removeFolder('tests');
    }

    private function removeFile(string $fileName)
    {
        $file = $this->packagePath . DIRECTORY_SEPARATOR . $fileName;

        $this->io->write('<info>Remove file: ' . $this->filesystem->normalizePath($file) . '</info>');

        if (file_exists($file)) {
            $this->filesystem->remove($file);
        }
    }

    private function removeFolder(string $folderName)
    {
        $folder = $this->packagePath . DIRECTORY_SEPARATOR . $folderName;

        $this->io->write('<info>Remove folder: ' . $this->filesystem->normalizePath($folder) . '</info>');

        $this->filesystem->removeDirectory($folder);
    }
}
