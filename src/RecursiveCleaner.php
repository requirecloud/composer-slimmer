<?php

namespace Druidfi\ComposerSlimmer;

use Composer\IO\IOInterface;
use Composer\Util\Filesystem;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

class RecursiveCleaner
{
    use UtilsTrait;

    private Filesystem $filesystem;

    private array $folders = [
        '.circleci',
        '.ddev',
        '.git',
        '.github',
        '.psalm',
        '.tugboat',
        'doc',
        'docs',
        'examples',
        //'migrations',
        'test',
        'tests',
    ];

    private array $extensions = [
        'dist',
        'md',
        'txt',
    ];

    private array $filesToRemove;

    private int $totalSize = 0;

    private array $matchingFolders = [];
    private array $matchingFiles = [];

    public function __construct(IOInterface $io = null)
    {
        $this->io = $io;
        $this->filesystem = new Filesystem();
        $this->filesToRemove = require __DIR__ . '/../data/files.php' ?? [];
    }

    public function clean(string $path, bool $dry_run = false): int
    {
        $rii = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path)
        );

        foreach ($rii as $file) {
            /** @var SplFileInfo $file */
            if ($file->isDir() && $file->getFilename() !== '..') {
                $folderParts = explode(DIRECTORY_SEPARATOR, $file->getPath());

                if (in_array(end($folderParts), $this->folders)) {
                    $this->matchingFolders[] = $file->getPathname();
                    $size = $this->filesystem->size($file->getRealPath());

                    if ($this->io && $this->io->isVerbose()) {
                        $this->write('Removing folder', $file, $size);
                    }

                    if ($dry_run) {
                        echo PHP_EOL . 'DRYRUN: removing folder ' . $file->getPath() . ' <> ' . $size;
                    } else {
                        $this->filesystem->remove($file->getRealPath());
                    }

                    $this->totalSize += $size;
                }
            }
            else {
                if (in_array($file->getExtension(), $this->extensions)) {
                    $this->matchingFiles[] = $file->getPathname();
                    $size = $this->filesystem->size($file->getRealPath());

                    if ($this->io && $this->io->isVerbose()) {
                        $this->write('Removing file', $file, $size);
                    }

                    if ($dry_run) {
                        echo PHP_EOL . 'DRYRUN: removing file ' . $file->getPath() . ' <> ' . $size;
                    } else {
                        $this->filesystem->remove($file->getRealPath());
                    }

                    $this->totalSize += $size;
                }
                else if (in_array($file->getFilename(), $this->filesToRemove)) {
                    $this->matchingFiles[] = $file->getPathname();
                    $size = $this->filesystem->size($file->getRealPath());

                    if ($this->io && $this->io->isVerbose()) {
                        $this->write('Removing file', $file, $size);
                    }

                    if ($dry_run) {
                        echo PHP_EOL . 'DRYRUN: removing file ' . $file->getPath() . ' <> ' . $size;
                    } else {
                        $this->filesystem->remove($file->getRealPath());
                    }

                    $this->totalSize += $size;
                }
            }
        }

        return $this->totalSize;
    }

    public function getMatchingFolders(): array
    {
        return $this->matchingFolders;
    }
}
