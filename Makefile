.PHONY: environment
environment:
	docker-compose up -d
	sleep 5 # wait test dependencies

.PHONY: apply-metadata
apply-metadata:
	php ./src/metadata/bin/hasura-metadata apply

.PHONY: check-cs
check-cs:
	./vendor/bin/ecs check

.PHONY: fix-cs
fix-cs:
	./vendor/bin/ecs check --fix

.PHONY: export-metadata
export-metadata:
	php ./src/metadata/bin/hasura-metadata export --force

.PHONY: sailor-introspect
sailor-introspect:
	php ./src/sailor-bridge/bin/hasura-sailor introspect

.PHONY: test-all
test-all:
	./vendor/bin/phpunit

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