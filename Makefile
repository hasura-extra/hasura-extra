.PHONY: apply-metadata
apply-metadata:
	php ./src/metadata/bin/hasura apply

.PHONY: export-metadata
export-metadata:
	php ./src/metadata/bin/hasura export --force

.PHONY: test-api-client
test-api-client:
	./vendor/bin/phpunit src/api-client

.PHONY: test-metadata
test-metadata:
	./vendor/bin/phpunit src/metadata