.PHONY: environment
environment:
	composer update
	docker-compose up -d

.PHONY: apply-metadata
apply-metadata:
	HASURA_BASE_URI="http://localhost:8082" \
	HASURA_ADMIN_SECRET="test" \
	HASURA_METADATA_PATH="$(PWD)/src/metadata/metadata" \
	php ./src/metadata/bin/hasura-metadata apply;

	HASURA_BASE_URI="http://localhost:8083" \
	HASURA_ADMIN_SECRET="test" \
	HASURA_METADATA_PATH="$(PWD)/src/graphqlite-bridge/metadata" \
	php ./src/metadata/bin/hasura-metadata apply;

	HASURA_BASE_URI="http://localhost:8084" \
	HASURA_ADMIN_SECRET="test" \
	HASURA_METADATA_PATH="$(PWD)/src/sailor-bridge/metadata" \
	php ./src/metadata/bin/hasura-metadata apply;

	HASURA_BASE_URI="http://localhost:8085" \
	HASURA_ADMIN_SECRET="test" \
	HASURA_METADATA_PATH="$(PWD)/src/bundle/metadata" \
	php ./src/metadata/bin/hasura-metadata apply; \

	HASURA_BASE_URI="http://localhost:8086" \
	HASURA_ADMIN_SECRET="test" \
	HASURA_METADATA_PATH="$(PWD)/src/laravel/metadata" \
	php ./src/metadata/bin/hasura-metadata apply;

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

.PHONY: test-sailor-bridge
test-sailor-bridge:
	./vendor/bin/phpunit src/sailor-bridge

.PHONY: test-auth-hook
test-auth-hook:
	./vendor/bin/phpunit src/auth-hook

.PHONY: test-graphqlite-bridge
test-graphqlite-bridge:
	./vendor/bin/phpunit src/graphqlite-bridge

.PHONY: test-bundle
test-bundle:
	./vendor/bin/phpunit src/bundle

.PHONY: test-laravel
test-laravel:
	./vendor/bin/phpunit src/laravel

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
	HASURA_METADATA_PATH="$(PWD)/src/graphqlite-bridge/metadata" \
	php ./src/metadata/bin/hasura-metadata export --force;

	HASURA_BASE_URI="http://localhost:8084" \
	HASURA_ADMIN_SECRET="test" \
	HASURA_METADATA_PATH="$(PWD)/src/sailor-bridge/metadata" \
	php ./src/metadata/bin/hasura-metadata export --force; \

	HASURA_BASE_URI="http://localhost:8084" \
	HASURA_ADMIN_SECRET="test" \
	HASURA_METADATA_PATH="$(PWD)/src/sailor-bridge/metadata" \
	SAILOR_QUERY_SPEC_PATH="$(PWD)/src/sailor-bridge/metadata" \
	SAILOR_SCHEMA_PATH="$(PWD)/src/sailor-bridge/metadata/schema.graphql" \
	SAILOR_EXECUTOR_PATH="$(PWD)/src/sailor-bridge/metadata" \
	SAILOR_EXECUTOR_NAMESPACE="App\\GraphqlExecutor" \
	php ./src/sailor-bridge/bin/hasura-sailor introspect;

	HASURA_BASE_URI="http://localhost:8085" \
	HASURA_ADMIN_SECRET="test" \
	HASURA_METADATA_PATH="$(PWD)/src/bundle/metadata" \
	php ./src/metadata/bin/hasura-metadata export --force; \

	HASURA_BASE_URI="http://localhost:8086" \
	HASURA_ADMIN_SECRET="test" \
	HASURA_METADATA_PATH="$(PWD)/src/laravel/metadata" \
	php ./src/metadata/bin/hasura-metadata export --force;