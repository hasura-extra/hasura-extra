<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge\RemoteSchemaProcessor;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Schema;
use GraphQL\Utils\SchemaPrinter;
use Hasura\ApiClient\Client;
use Hasura\GraphQLiteBridge\Attribute\Roles;
use Hasura\GraphQLiteBridge\Field\AnnotationTracker;
use Hasura\GraphQLiteBridge\RemoteSchemaInterface;
use Hasura\GraphQLiteBridge\RemoteSchemaProcessorInterface;
use Hasura\Metadata\MetadataUtils;

final class PermissionProcessor implements RemoteSchemaProcessorInterface
{
    public function __construct(
        private Schema $schema,
        private Client $client,
        private AnnotationTracker $annotationTracker,
        private string $dummyQueryField = PermissionDummyQuery::NAME
    ) {
    }

    public function process(RemoteSchemaInterface $remoteSchema): void
    {
        $metadataApi = $this->client->metadata();
        $data = $metadataApi->query('export_metadata', [], 2);
        $metadata = MetadataUtils::normalizeMetadata($data['metadata']);
        $metadata['remote_schemas'] ??= [];

        $updated = false;

        foreach ($metadata['remote_schemas'] as &$item) {
            if ($item['name'] === $remoteSchema->getName()) {
                $item['permissions'] = $this->createRemoteSchemaPermissions();
                $updated = true;

                break;
            }
        }

        if (false === $updated) {
            throw new \LogicException(sprintf('You should be add `%s` remote schema first!', $remoteSchema->getName()));
        }

        $metadataApi->query(
            'reload_metadata',
            [
                'reload_remote_schemas' => true,
                'reload_sources' => true
            ]
        );
        $metadataApi->query(
            'replace_metadata',
            [
                'metadata' => $metadata,
                'allow_inconsistent_metadata' => false
            ],
            2
        );
    }


    private function createRemoteSchemaPermissions(): array
    {
        $permissions = [];
        $roleFields = $this->collectRoleFields();

        foreach ($roleFields as $role => $fields) {
            $permissions[] = [
                'role' => $role,
                'definition' => [
                    'schema' => $this->getSchemaDefinition($fields),
                ],
            ];
        }

        return $permissions;
    }

    private function collectRoleFields(): array
    {
        $roleFields = [];
        $queryFields = $this->annotationTracker->getQueryFieldAnnotations(Roles::class);
        $mutationFields = $this->annotationTracker->getMutationFieldAnnotations(Roles::class);

        foreach ($queryFields as $field => $roles) {
            /** @var Roles $roles */
            $roles = $roles[0];

            foreach ($roles->getNames() as $role) {
                $roleFields[$role]['query'][] = $field;
            }
        }

        foreach ($mutationFields as $field => $roles) {
            /** @var Roles $roles */
            $roles = $roles[0];

            foreach ($roles->getNames() as $role) {
                $roleFields[$role]['mutation'][] = $field;
            }
        }

        return $roleFields;
    }

    private function getSchemaDefinition(array $fields): string
    {
        $queryFields = $fields['query'] ?? [$this->dummyQueryField];
        $mutationFields = $fields['mutation'] ?? [];
        $schemaConfig = [];
        $operationTypes[] = 'query: Query';

        $schemaConfig['query'] = $this->newObjectTypeByFields($this->schema->getQueryType(), $queryFields);

        if (!empty($mutationFields)) {
            $schemaConfig['mutation'] = $this->newObjectTypeByFields($this->schema->getMutationType(), $mutationFields);
            $operationTypes[] = 'mutation: Mutation';
        }

        $schema = sprintf("schema {\n %s \n}", implode("\n", $operationTypes));
        $types = SchemaPrinter::doPrint(new Schema($schemaConfig), ['commentDescriptions' => true]);

        return sprintf("%s \n %s", $schema, $types);
    }

    private function newObjectTypeByFields(ObjectType $type, array $fields): ObjectType
    {
        $config = $type->config;
        $config['fields'] = array_filter(
            $type->getFields(),
            fn($field) => in_array($field, $fields, true),
            ARRAY_FILTER_USE_KEY
        );

        return new ObjectType($config);
    }
}