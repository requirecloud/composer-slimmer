# Composer Slimmer

## Plan of Cleanup

- Remove listed folders (so we don't need to search inside them)
- Find all files with patterns e.g. *.{md,txt,dist}
- Make list of files to be removed from found files and listed files
- Remove files
- Report the size of removed files and folders

## How to test

Include in the project, accept plugin:

```shell
composer require druidfi/composer-slimmer:dev-main
```

Clean projects vendor and other folder loaded by Composer:

```shell
make clean
```

Test installing with slimmer plugin (`-v` flag is for verbose output):

```shell
composer install -v
```

At the end you should see how much space was saved.

## Resources

- [Composer issue #1750](https://github.com/composer/composer/issues/1750)
- [Drupal Clean Package Composer Plugin](https://www.drupal.org/project/clean_package)
- [The Drupal Vendor Hardening Composer Plugin](https://github.com/drupal/core-vendor-hardening)
