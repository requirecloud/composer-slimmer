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

        $this->io->write('<info>Remove file:</info> <comment> ' . $this->nice($file) . '</comment>');

        if (file_exists($file)) {
            $this->filesystem->remove($file);
        }
    }

    private function removeFolder(string $folderName)
    {
        $folder = $this->packagePath . DIRECTORY_SEPARATOR . $folderName;

        $this->io->write('<info>Remove folder:</info> <comment>' . $this->nice($folder) . '</comment>');

        $this->filesystem->removeDirectory($folder);
    }

    private function nice(string $path): string
    {
        return str_replace(getcwd(), '', $this->filesystem->normalizePath($path));
    }
}
