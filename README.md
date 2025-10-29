# Composer Slimmer

[![Run tests](https://github.com/requirecloud/composer-slimmer/actions/workflows/tests.yml/badge.svg)](https://github.com/requirecloud/composer-slimmer/actions/workflows/tests.yml)

## Plan of Cleanup

- Remove listed folders (so we don't need to search inside them)
- Find all files with patterns e.g. *.{md,txt,rst,dist}
- Make list of files to be removed from found files and listed files
- Remove files
- Report the size of removed files and folders

## How to test

Require Plugin in the project and accept plugin:

```console
composer require requirecloud/composer-slimmer
```

Clean projects vendor and other folders loaded by Composer.

Then test installing with Composer Slimmer plugin (`-v` flag is for verbose output):

```console
composer install -v
```

At the end you should see how much space was saved.

## Resources

- [Composer issue #1750](https://github.com/composer/composer/issues/1750)
- [Drupal Clean Package Composer Plugin](https://www.drupal.org/project/clean_package)
- [The Drupal Vendor Hardening Composer Plugin](https://github.com/drupal/core-vendor-hardening)
