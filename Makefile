DIR := tests/tmp

PHONY += --install
--install:
	rm -rf $(DIR) && mkdir -p $(DIR)
	cp tests/composer.json $(DIR)/composer.json
	composer --working-dir=$(DIR) install -v
	composer --working-dir=$(DIR) require drupal/admin_toolbar -v
	composer --working-dir=$(DIR) require drupal/paragraphs -v

PHONY += test
test: --install assert

PHONY += assert
assert: VENDOR := $(DIR)/vendor
assert:
	@[ ! -f $(VENDOR)/drupal/admin_toolbar/README.md ] || echo "Admin Toolbar README.md not cleaned!"
	@[ ! -f $(VENDOR)/paragraphs/modules/paragraphs_type_permissions/tests ] || echo "Paragraphs submodule tests folder not cleaned!"
	@[ -f $(VENDOR)/drush/drush/README.md ] || echo "Drush README.md cleaned but was not supposed to!"
