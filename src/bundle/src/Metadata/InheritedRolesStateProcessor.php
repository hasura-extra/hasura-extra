<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Metadata;

use Hasura\GraphQLiteBridge\Controller\DummyQuery;
use Hasura\Metadata\ManagerInterface;
use Hasura\Metadata\NotExistRemoteSchemaException;
use Hasura\Metadata\RemoteSchemaInterface;
use Hasura\Metadata\StateProcessorInterface;

final class InheritedRolesStateProcessor implements StateProcessorInterface
{
    public function __construct(
        private array $hierarchyRoles,
        private ?RemoteSchemaInterface $remoteSchema = null
    ) {
    }

    public function process(ManagerInterface $manager, bool $allowInconsistent = false): void
    {
        $metadata = $manager->exportToArray();
        $currentRoles = $this->collectCurrentRoles($metadata);
        $items = [];

        foreach ($this->hierarchyRoles as $role => $set) {
            $diff = array_diff($set, $currentRoles);

            if (!empty($diff)) {
                if (null === $this->remoteSchema) {
                    throw new ChildRoleMissingException(
                        sprintf(
                            'Can not create inherited role: `%s`, missing child roles: [%s]',
                            $role,
                            implode(', ', $diff)
                        )
                    );
                }

                // auto add missing role via remote schema permissions
                $this->addDummyRemoteSchemaPermissions($diff, $metadata);
            }

            $items[] = [
                'role_name' => $role,
                'role_set' => $set,
            ];
        }

        $metadata['inherited_roles'] = $items;

        $manager->applyFromArray($metadata, $allowInconsistent);
    }

    private function addDummyRemoteSchemaPermissions(array $roleSet, array &$metadata): void
    {
        $added = false;
        $remoteSchemas = $metadata['remote_schemas'] ?? [];
        $permissionsAppend = array_map(
            fn (string $role) => [
                'role' => $role,
                'definition' => [
                    'schema' => sprintf(
                        'schema { query: Query } type Query { %s: String! }',
                        DummyQuery::NAME
                    ),
                ],
            ],
            $roleSet
        );

        foreach ($remoteSchemas as &$remoteSchema) {
            if ($remoteSchema['name'] === $this->remoteSchema->getName()) {
                $added = true;
                $remoteSchema['permissions'] = array_merge(
                    $remoteSchema['permissions'] ?? [],
                    $permissionsAppend
                );

                break;
            }
        }

        if (false === $added) {
            throw new NotExistRemoteSchemaException($this->remoteSchema->getName());
        }

        $metadata['remote_schemas'] = $remoteSchemas;
    }

    private function collectCurrentRoles(array $metadata): array
    {
        $roles = [];

        foreach ($metadata['remote_schemas'] ?? [] as $remoteSchema) {
            $roles = array_merge(
                $roles,
                array_column($remoteSchema['permissions'] ?? [], 'role')
            );
        }

        foreach ($metadata['sources'] ?? [] as $source) {
            foreach ($source['tables'] as $table) {
                foreach (['select', 'insert', 'update', 'delete'] as $operation) {
                    $operationPermissions = sprintf('%s_permissions', $operation);
                    $roles = array_merge(
                        $roles,
                        array_column($table[$operationPermissions] ?? [], 'role')
                    );
                }
            }
        }

        return array_values(array_unique($roles));
    }
}
