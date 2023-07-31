<?php

namespace Druidfi\ComposerSlimmer;

use Composer\IO\IOInterface;
use Composer\Util\Filesystem;
use Exception;
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
        '.idea',
        '.phpunit.cache',
        '.psalm',
        '.scenarios.lock',
        '.tugboat',
        '.vscode',
        'doc',
        'docs',
        'examples',
        //'migrations',
        'test',
        'tests',
        'vendor',
    ];

    private array $extensions = ['dist', 'md', 'rst', 'txt'];
    private array $excluded = [];
    private array $filesToRemove;
    private int $totalSize = 0;
    private array $matchingFolders = [];
    private array $matchingFiles = [];
    private bool $dryRun = false;

    public function __construct(IOInterface $io = null)
    {
        $this->io = $io;
        $this->filesystem = new Filesystem();
        $this->filesToRemove = require __DIR__ . '/../data/files.php' ?? [];
    }

    public function clean(string $path, array $extra = []): int
    {
        $folders = $extra['folders'] ?? [];
        $exclude = $extra['exclude'] ?? [];

        $rii = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path)
        );

        $this->folders = array_merge($this->folders, $folders);

        foreach ($rii as $file) {
            if (in_array($file->getFilename(), $exclude, true)) {
                continue;
            }

            /** @var SplFileInfo $file */
            if ($file->isDir() && $file->getFilename() !== '..') {
                $relativePath = $this->getRelativePath($path, $file);

                if (in_array($relativePath, $exclude, true)) {
                    continue;
                }

                $folderParts = explode(DIRECTORY_SEPARATOR, $file->getPath());

                if (in_array($relativePath, $this->folders, true) ||
                    in_array(end($folderParts), $this->folders, true)) {
                    $this->handleItem($file);
                }
            }
            else {
                if (in_array($file->getExtension(), $this->extensions)) {
                    $this->handleItem($file);
                }
                else if (in_array($file->getFilename(), $this->filesToRemove)) {
                    $this->handleItem($file);
                }
            }
        }

        return $this->totalSize;
    }

    public function getMatchingFolders(): array
    {
        return $this->matchingFolders;
    }

    private function handleItem($file): void
    {
        $resource = ($file->isDir()) ? 'folder' : 'file';

        try {
            $size = $this->filesystem->size($file->getRealPath());
        }
        catch (Exception $e) {
            return;
        }

        if ($resource === 'folder') {
            $this->matchingFolders[] = $file->getPathname();
        }
        else {
            $this->matchingFiles[] = $file->getPathname();
        }

        if ($this->io && $this->io->isVerbose()) {
            $this->write('Removing ' . $resource, $file, $size);
        }

        if ($this->dryRun) {
            echo PHP_EOL . 'DRYRUN: removing ' . $resource . ' ' . $file->getPath() . ' <> ' . $size;
        } else {
            $this->filesystem->remove($file->getRealPath());
        }

        $this->totalSize += $size;
    }

    private function getRelativePath(string $path, SplFileInfo $file): string
    {
        $path = str_replace($path . DIRECTORY_SEPARATOR, '', $file->getPathname());
        return str_replace('/.', '', $path);
    }
}
