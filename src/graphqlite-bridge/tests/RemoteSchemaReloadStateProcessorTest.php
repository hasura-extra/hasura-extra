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
use Hasura\GraphQLiteBridge\RemoteSchemaReloadStateProcessor;

final class RemoteSchemaReloadStateProcessorTest extends TestCase
{
    public function testProcess(): void
    {
        $this->expectNotToPerformAssertions();

        $processor = new RemoteSchemaReloadStateProcessor(
            new RemoteSchema('graphqlite-bridge'),
            $this->client
        );

        $processor->process();
    }

    public function testProcessWithRemoteSchemaDoesNotExist(): void
    {
        $this->expectException(NotExistRemoteSchemaException::class);

        $processor = new RemoteSchemaReloadStateProcessor(
            new RemoteSchema('not-exist'),
            $this->client
        );

        $processor->process();
    }
}