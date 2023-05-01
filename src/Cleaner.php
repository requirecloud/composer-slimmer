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

    private array $files = [
        '.codeclimate.yml',
        '.csslintrc',
        '.eslintignore',
        '.eslintrc',
        '.eslintrc.json',
        '.gitattributes',
        '.gitignore',
        '.gitlab-ci.yml',
        '.prettierignore',
        '.prettierrc.json',
        '.travis.yml',
        '.travis-phpcs.sh',
        'CHANGELOG.md',
        'CHANGELOG.txt',
        'CODE_OF_CONDUCT.txt',
        'CODEOWNERS',
        'CONTRIBUTING.md',
        'composer.lock',
        'docker-compose.yml',
        'drupalci.yml',
        'INSTALL.md',
        'ludwig.json',
        'NOTES.md',
        'package.json',
        'PATCHES.txt',
        'phpcs.xml',
        'phpcs.xml.dist',
        'phpstan.neon.dist',
        'phpunit.core.xml.dist',
        'README.md',
        'README.txt',
        'UPDATE.md',
        'webpack.config.js',
        'yarn.lock',
    ];

    private array $folders = [
        '.circleci',
        '.ddev',
        '.git',
        '.github',
        '.tugboat',
        'docs',
        'examples',
        //'migrations',
        'tests',
    ];

    public function __construct(IOInterface $io)
    {
        $this->io = $io;
        $this->filesystem = new Filesystem();
    }

    public function clean(Package $package, string $packagePath): int
    {
        $this->packagePath = $packagePath;

        //$this->io->write('<info>Clean up on ' . $package->getName() . '</info>');

        $totalSize = 0;

        foreach ($this->files as $file) {
            $totalSize += $this->removeFile($file);
        }

        foreach ($this->folders as $folder) {
            $totalSize += $this->removeFolder($folder);
        }

        return $totalSize;
    }

    private function removeFile(string $fileName): int
    {
        $file = $this->packagePath . DIRECTORY_SEPARATOR . $fileName;

        if (file_exists($file)) {
            $size = $this->filesystem->size($file);
            $this->write('Removing file', $file .' ('. $this->size($size) .')');
            $this->filesystem->remove($file);

            return $size;
        }

        return 0;
    }

    private function removeFolder(string $folderName): int
    {
        $folder = $this->packagePath . DIRECTORY_SEPARATOR . $folderName;

        if (file_exists($folder)) {
            $size = $this->filesystem->size($folder);
            $this->write('Removing folder', $folder . ' (' . $this->size($size) . ')');
            $this->filesystem->removeDirectory($folder);

            return $size;
        }

        return 0;
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

    public static function size(int $bytes, int $dec = 2): string
    {
        $size = ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $factor = floor((strlen($bytes) - 1) / 3);
        if ($factor == 0) $dec = 0;

        return sprintf("%.{$dec}f %s", $bytes / (1024 ** $factor), $size[$factor]);
    }
}
