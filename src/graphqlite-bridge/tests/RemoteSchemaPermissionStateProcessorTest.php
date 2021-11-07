<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge\Tests;

use Hasura\GraphQLiteBridge\NotExistRemoteSchemaException;
use Hasura\GraphQLiteBridge\RemoteSchema;
use Hasura\GraphQLiteBridge\RemoteSchemaPermissionStateProcessor;

final class RemoteSchemaPermissionStateProcessorTest extends TestCase
{
    protected bool $autoBackupAndRestoreMetadata = true;

    public function testCanThrowErrorWhenRemoteSchemaDoesNotExist(): void
    {
        $this->expectException(NotExistRemoteSchemaException::class);
        $this->expectExceptionMessage('You should be add `test` remote schema first!');

        $processor = new RemoteSchemaPermissionStateProcessor(
            new RemoteSchema('test'),
            $this->schema,
            $this->client,
            $this->annotationTracker
        );

        $processor->process();
    }

    public function testCanSyncRolePermissions(): void
    {
        $remoteSchema = $this->fetchRemoteSchemaMetadata();

        $this->assertSame('graphqlite-bridge', $remoteSchema['name']);
        $this->assertArrayNotHasKey('permissions', $remoteSchema);

        $processor = new RemoteSchemaPermissionStateProcessor(
            new RemoteSchema('graphqlite-bridge'),
            $this->schema,
            $this->client,
            $this->annotationTracker
        );
        $processor->process();

        $remoteSchema = $this->fetchRemoteSchemaMetadata();

        $this->assertSame('graphqlite-bridge', $remoteSchema['name']);
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

        return $data['metadata']['remote_schemas'][1];
    }
}