<?php

namespace Druidfi\DrupalSlimmer;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class Finder
{
    private array $common;

    public function __construct()
    {
        $this->common = require __DIR__ . '/../data/common.php';
    }

    public function find(string $path): array
    {
        $directory = new RecursiveDirectoryIterator($path);
        $iterator = new RecursiveIteratorIterator($directory);
        $files = [];

        foreach ($iterator as $item) {
            // Skip directories that are in the common folders list.
            if ($item->isDir() && in_array($item->getFilename(), $this->common['folders'], true)) {
                continue;
            }
            //if (...custom conditions...) {
                $files[] = $item->getPathname();
            //}
        }

        return $files;
    }
}
