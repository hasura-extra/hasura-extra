<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Schema;
use GraphQL\Utils\SchemaPrinter;
use Hasura\GraphQLiteBridge\Attribute\Roles;
use Hasura\GraphQLiteBridge\Controller\DummyQuery;
use Hasura\GraphQLiteBridge\Field\AnnotationTracker;
use Hasura\Metadata\ManagerInterface;
use Hasura\Metadata\NotExistRemoteSchemaException;
use Hasura\Metadata\RemoteSchemaInterface;
use Hasura\Metadata\StateProcessorInterface;

final class RemoteSchemaPermissionStateProcessor implements StateProcessorInterface
{
    public function __construct(
        private RemoteSchemaInterface $remoteSchema,
        private Schema $schema,
        private AnnotationTracker $annotationTracker,
        private string $dummyQueryField = DummyQuery::NAME
    ) {
        // eager loading all query/mutation fields.
        $this->schema->assertValid();
    }

    public function process(ManagerInterface $manager, bool $allowInconsistent = false): void
    {
        $remoteSchema = $this->remoteSchema;
        $metadata = $manager->exportToArray();
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
            throw new NotExistRemoteSchemaException($remoteSchema->getName());
        }

        $manager->applyFromArray($metadata, $allowInconsistent);
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