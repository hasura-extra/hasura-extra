<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge\Tests;

use Hasura\GraphQLiteBridge\RemoteSchemaPermissionStateProcessor;
use Hasura\Metadata\Manager;
use Hasura\Metadata\NotExistRemoteSchemaException;
use Hasura\Metadata\OperatorInterface;
use Hasura\Metadata\RemoteSchema;

final class RemoteSchemaPermissionStateProcessorTest extends TestCase
{
    protected bool $autoBackupAndRestoreMetadata = true;

    public function testCanThrowErrorWhenRemoteSchemaDoesNotExist(): void
    {
        $this->expectException(NotExistRemoteSchemaException::class);
        $this->expectExceptionMessage('Remote schema: `test` not exist, Did you forget to add it?');

        $manager = new Manager($this->client, '', $this->createMock(OperatorInterface::class));
        $processor = new RemoteSchemaPermissionStateProcessor(
            new RemoteSchema('test'),
            $this->schema,
            $this->annotationTracker
        );

        $processor->process($manager);
    }

    public function testCanSyncRolePermissions(): void
    {
        $remoteSchema = $this->fetchRemoteSchemaMetadata();

        $this->assertSame('graphqlite-bridge', $remoteSchema['name']);
        $this->assertArrayNotHasKey('permissions', $remoteSchema);

        $manager = new Manager($this->client, '', $this->createMock(OperatorInterface::class));
        $processor = new RemoteSchemaPermissionStateProcessor(
            new RemoteSchema('graphqlite-bridge'),
            $this->schema,
            $this->annotationTracker
        );
        $processor->process($manager);

        $remoteSchema = $this->fetchRemoteSchemaMetadata();

        $this->assertArrayHasKey('permissions', $remoteSchema);

        $roles = array_column($remoteSchema['permissions'], 'role');

        $this->assertContains('A', $roles);
        $this->assertContains('B', $roles);
        $this->assertContains('C', $roles);

        $definitions = array_column($remoteSchema['permissions'], 'definition');
        $roleDefinitions = array_combine($roles, $definitions);

        $this->assertStringContainsString(
        /** @lang GraphQL */
            <<<SDL
type Query { dummy: String!
}
SDL,
            $roleDefinitions['A']['schema']
        );
        $this->assertStringContainsString(
        /** @lang GraphQL */
            <<<SDL
type Query { dummy: String!
}
SDL,
            $roleDefinitions['B']['schema']
        );
        $this->assertStringContainsString(
        /** @lang GraphQL */
            <<<SDL
type Query { _dummy: String!
}
SDL,
            $roleDefinitions['C']['schema']
        );
    }

    private function fetchRemoteSchemaMetadata(): array
    {
        $data = $this->client->metadata()->query('export_metadata', [], 2);
        $remoteSchemas = array_column($data['metadata']['remote_schemas'], null, 'name');

        return $remoteSchemas['graphqlite-bridge'];
    }
}