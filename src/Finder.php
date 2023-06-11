<?php

namespace Druidfi\ComposerSlimmer;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

class Finder
{
    private array $foundFiles = [];
    private array $foundFolders = [];

    public function find(string $path): array
    {
        $directory = new RecursiveDirectoryIterator($path);
        $filter = new RecursiveFilterIterator($directory);
        $iterator = new RecursiveIteratorIterator($filter);

        /** @var SplFileInfo $item */
        foreach ($iterator as $item) {
            echo $item->getPathname() . PHP_EOL;
        }

        return [
            'files' => $this->foundFiles,
            'folders' => $this->foundFolders,
        ];
    }
}
