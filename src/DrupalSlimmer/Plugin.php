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
use Composer\Installer\PackageEvent;
use Exception;

class TemplateInstallerPlugin implements PluginInterface, EventSubscriberInterface
{
    public function activate(Composer $composer, IOInterface $io)
    {
        $io->write('<info>Activate</info>');
    }

    public function deactivate(Composer $composer, IOInterface $io)
    {
        $io->write('<info>Deactivate</info>');
    }

    public function uninstall(Composer $composer, IOInterface $io)
    {
        $io->write('<info>Uninstall</info>');
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
        $package = $this->getTargetPackage($event->getOperation());
        $package_name = $package->getName();

        $event->getIO()->write('<info>cleanUp on ' . $package_name . '</info>');
    }

    private function getTargetPackage($operation): Package
    {
        if ($operation instanceof InstallOperation) {
            return $operation->getPackage();
        }
        else if ($operation instanceof UpdateOperation) {
            return $operation->getTargetPackage();
        }

        throw new Exception('Unknown operation: ' . get_class($operation));
    }
}
