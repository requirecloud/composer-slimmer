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

    private int $totalSize = 0;

    private array $matchingFolders = [];

    public function __construct(IOInterface $io = null)
    {
        $this->io = $io;
        $this->filesystem = new Filesystem();
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

                    if ($this->io && $this->io->isVerbose()) {
                        $this->write('Removing file', $file, $file->getSize());
                    }

                    if ($dry_run) {
                        echo PHP_EOL . 'DRYRUN: removing folder ' . $file->getPath() . ' <> ' . $file->getSize();
                    } else {
                        $this->filesystem->remove($file->getRealPath());
                    }

                    $this->totalSize += $this->filesystem->size($file->getRealPath());
                    //$this->totalSize += $file->getSize();
                }
            }
            else {
                //echo 'FILE: ' . $file->getPathname() .' <> ' . $file->getFilename() . PHP_EOL;
            }
        }

        return $this->totalSize;
    }

    public function getMatchingFolders(): array
    {
        return $this->matchingFolders;
    }
}
