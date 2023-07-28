PHONY += test
test: DIR := tests/tmp
test:
	rm -rf $(DIR) && mkdir -p $(DIR)
	cp tests/composer.json $(DIR)/composer.json
	composer --working-dir=$(DIR) install -v
	composer --working-dir=$(DIR) require drupal/admin_toolbar -v
