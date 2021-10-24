.PHONY: apply-metadata
apply-metadata:
	php ./src/metadata/bin/hasura apply

.PHONY: export-metadata
export-metadata:
	php ./src/metadata/bin/hasura export --force