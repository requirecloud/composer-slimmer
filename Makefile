PHONY += test-codebase
test-codebase: DIR := tests/tmp
test-codebase:
	rm -rf $(DIR) && mkdir -p $(DIR)
	cp tests/composer.json $(DIR)/composer.json
	composer --working-dir=$(DIR) install -v
