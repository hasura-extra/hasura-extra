.PHONY: environment
environment:
	composer update
	docker compose up -d

.PHONY: apply-metadata-metadata
apply-metadata-metadata:
	HASURA_BASE_URI="http://localhost:8082" \
	HASURA_ADMIN_SECRET="test" \
	HASURA_METADATA_PATH="$(PWD)/src/metadata/metadata" \
	php ./src/metadata/bin/hasura-metadata apply;

.PHONY: apply-metadata-bundle
apply-metadata-bundle:
	HASURA_BASE_URI="http://localhost:8083" \
	HASURA_ADMIN_SECRET="test" \
	HASURA_METADATA_PATH="$(PWD)/src/bundle/metadata" \
	php ./src/metadata/bin/hasura-metadata apply;

.PHONY: apply-metadata
apply-metadata:
	set -e; \
	make apply-metadata-metadata; \
	make apply-metadata-bundle;

.PHONY: check-cs
check-cs:
	./vendor/bin/ecs check

.PHONY: fix-cs
fix-cs:
	./vendor/bin/ecs check --fix

.PHONY: test-all
test-all:
	./vendor/bin/phpunit --coverage-clover build/logs/phpunit/clover.xml --log-junit build/logs/phpunit/junit.xml

.PHONY: test-api-client
test-api-client:
	./vendor/bin/phpunit src/api-client

.PHONY: test-metadata
test-metadata:
	./vendor/bin/phpunit src/metadata

.PHONY: test-event-dispatcher
test-event-dispatcher:
	./vendor/bin/phpunit src/event-dispatcher

.PHONY: test-auth-hook
test-auth-hook:
	./vendor/bin/phpunit src/auth-hook

.PHONY: test-bundle
test-bundle:
	./vendor/bin/phpunit src/bundle

.PHONY: test-graphql-scalars
test-graphql-scalars:
	./vendor/bin/phpunit src/graphql-scalars

.PHONY: changelog
changelog:
	docker run -it --rm -v "$(PWD)":/usr/local/src/your-app ferrarimarco/github-changelog-generator -u hasura-extra -p hasura-extra --exclude-tags-regex "helm-chart-*" --output= --unreleased-only --token=$$GITHUB_TOKEN --no-issues --usernames-as-github-logins --no-verbose

.PHONY: export-metadata
export-metadata:
	HASURA_BASE_URI="http://localhost:8082" \
	HASURA_ADMIN_SECRET="test" \
	HASURA_METADATA_PATH="$(PWD)/src/metadata/metadata" \
	php ./src/metadata/bin/hasura-metadata export --force;

	HASURA_BASE_URI="http://localhost:8083" \
	HASURA_ADMIN_SECRET="test" \
	HASURA_METADATA_PATH="$(PWD)/src/bundle/metadata" \
	php ./src/metadata/bin/hasura-metadata export --force; \


.PHONY: binary-test
binary-test:
	HASURA_BASE_URI= \
	HASURA_ADMIN_SECRET= \
	HASURA_METADATA_PATH= \
	php ./src/metadata/bin/hasura-metadata;
