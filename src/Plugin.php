<?php

namespace Druidfi\ComposerSlimmer;

use Composer\Composer;
use Composer\DependencyResolver\Operation\InstallOperation;
use Composer\DependencyResolver\Operation\UpdateOperation;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\InstallationManager;
use Composer\Installer\PackageEvents;
use Composer\IO\IOInterface;
use Composer\Package\Package;
use Composer\Plugin\PluginEvents;
use Composer\Plugin\PluginInterface;
use Composer\Installer\PackageEvent;
use Composer\Plugin\PostFileDownloadEvent;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;

class Plugin implements PluginInterface, EventSubscriberInterface
{
    private Cleaner $cleaner;
    private int $totalSize = 0;
    private ?InstallationManager $manager = null;

    public function activate(Composer $composer, IOInterface $io): void
    {
        $this->cleaner = new Cleaner($io);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PackageEvents::POST_PACKAGE_INSTALL => ['cleanUp', 9],
            PackageEvents::POST_PACKAGE_UPDATE => ['cleanUp', 9],
            PluginEvents::POST_FILE_DOWNLOAD => ['cleanUp', 9],
            ScriptEvents::POST_UPDATE_CMD => ['end', -20],
            ScriptEvents::POST_INSTALL_CMD => ['end', -20],
        ];
    }

    public function cleanUp(PackageEvent|PostFileDownloadEvent $event): void
    {
        $package = $this->getPackage($event->getOperation());

        if ($package) {
            $packagePath = $this->getPackagePath($event, $package);

            if ($packagePath) {
                $this->totalSize += $this->cleaner->clean($package, $packagePath);
            }
        }
    }

    public function end(Event $event): void
    {
        if ($this->totalSize > 0) {
            $totalSize = Cleaner::size($this->totalSize);
            $message = sprintf('> <info>druidfi/composer-slimmer</info>: Total of <comment>%s</comment> was removed.', $totalSize);

            $event->getIO()->write($message);
        }
    }

    private function getPackage($operation): ?Package
    {
        if ($operation instanceof InstallOperation) {
            return $operation->getPackage();
        }
        else if ($operation instanceof UpdateOperation) {
            return $operation->getTargetPackage();
        }

        return null;
    }

    private function getPackagePath(PackageEvent|PostFileDownloadEvent $event, Package $package): ?string
    {
        if (!$this->manager) {
            $this->manager = $event->getComposer()->getInstallationManager();
        }

        return $this->manager->getInstaller($package->getType())->getInstallPath($package);
    }

    public function deactivate(Composer $composer, IOInterface $io) {}

    public function uninstall(Composer $composer, IOInterface $io) {}
}
