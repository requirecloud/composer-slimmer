<?php

namespace Druidfi\ComposerSlimmer;

use RecursiveFilterIterator as BaseRecursiveFilterIterator;
use RecursiveIterator;

class RecursiveFilterIterator extends BaseRecursiveFilterIterator {

    public static $FILTERS = [
        'md',
        'txt',
        'dist',
    ];

    private array $common;

    public function __construct(RecursiveIterator $iterator)
    {
        parent::__construct($iterator);

        $this->common = require __DIR__ . '/../data/common.php';
    }

    public function accept(): bool
    {
        if (in_array($this->current()->getExtension(), self::$FILTERS, true)) {
            return true;
        }

        return false;
    }
}
