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

class Plugin implements PluginInterface, EventSubscriberInterface
{
    private Cleaner $cleaner;

    public function activate(Composer $composer, IOInterface $io)
    {
        $this->cleaner = new Cleaner($io);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PackageEvents::POST_PACKAGE_INSTALL => 'cleanUp',
            PackageEvents::POST_PACKAGE_UPDATE => 'cleanUp',
        ];
    }

    public function cleanUp(PackageEvent $event)
    {
        $package = $this->getPackage($event->getOperation());

        if ($package) {
            $packagePath = $this->getPackagePath($package);

            if ($packagePath) {
                $this->cleaner->clean($package, $packagePath);
            }
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

    private function getPackagePath(Package $package): ?string
    {
        return InstalledVersions::getInstallPath($package->getPrettyName());
    }

    public function deactivate(Composer $composer, IOInterface $io) {}

    public function uninstall(Composer $composer, IOInterface $io) {}
}
