# Composer Slimmer

## Plan of Cleanup

- Remove listed folders (so we don't need to search inside them)
- Find all files with patterns e.g. *.{md,txt,dist}
- Make list of files to be removed from found files and listed files
- Remove files
- Report the size of removed files and folders

## Resources

- [Composer issue #1750](https://github.com/composer/composer/issues/1750)
- [Drupal Clean Package Composer Plugin](https://www.drupal.org/project/clean_package)
- [The Drupal Vendor Hardening Composer Plugin](https://github.com/drupal/core-vendor-hardening)
