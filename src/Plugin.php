<?php

namespace Druidfi\DrupalSlimmer;

use Composer\Composer;
use Composer\DependencyResolver\Operation\InstallOperation;
use Composer\DependencyResolver\Operation\UpdateOperation;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\PackageEvents;
use Composer\IO\IOInterface;
use Composer\Package\Package;
use Composer\Plugin\PluginInterface;
use Composer\InstalledVersions;
use Composer\Installer\PackageEvent;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;

class Plugin implements PluginInterface, EventSubscriberInterface
{
    private Cleaner $cleaner;
    private int $totalSize = 0;

    public function activate(Composer $composer, IOInterface $io)
    {
        $this->cleaner = new Cleaner($io);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PackageEvents::POST_PACKAGE_INSTALL => 'cleanUp',
            PackageEvents::POST_PACKAGE_UPDATE => 'cleanUp',
            ScriptEvents::POST_UPDATE_CMD => 'end',
            ScriptEvents::POST_INSTALL_CMD => 'end',
        ];
    }

    public function cleanUp(PackageEvent $event)
    {
        $package = $this->getPackage($event->getOperation());

        if ($package) {
            $packagePath = $this->getPackagePath($package);

            if ($packagePath) {
                $this->totalSize += $this->cleaner->clean($package, $packagePath);
            }
        }
    }

    public function end(Event $event)
    {
        $event->getIO()->write('Total of '. Cleaner::size($this->totalSize) .' was removed.');
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

    private function getPackagePath(Package $package): ?string
    {
        return InstalledVersions::getInstallPath($package->getPrettyName());
    }

    public function deactivate(Composer $composer, IOInterface $io) {}

    public function uninstall(Composer $composer, IOInterface $io) {}
}
