<?php

use PHPUnit\Framework\Attributes\CoversClass;
use Druidfi\ComposerSlimmer\RecursiveCleaner;
use PHPUnit\Framework\TestCase;

#[CoversClass(RecursiveCleaner::class)]
class FinderTest extends TestCase
{
    public function testFoobar()
    {
        $recursiveCleaner = new RecursiveCleaner();
        $totalSize = $recursiveCleaner->clean('vendor');
        $folders = $recursiveCleaner->getMatchingFolders();
        //var_dump($totalSize, $folders);

        $this->assertGreaterThan(0, $totalSize);
        $this->assertContains('vendor/sebastian/code-unit-reverse-lookup/.psalm/.', $folders);
    }
}
