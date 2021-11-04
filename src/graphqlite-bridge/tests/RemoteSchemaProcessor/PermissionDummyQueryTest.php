<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge\Tests\RemoteSchemaProcessor;

use GraphQL\GraphQL;
use Hasura\GraphQLiteBridge\Tests\TestCase;

final class PermissionDummyQueryTest extends TestCase
{
    public function testCanExecuteDummyQuery(): void
    {
        $schema = $this->schemaFactory->createSchema();
        $result = GraphQL::executeQuery($schema, 'query { _dummy }');

        $this->assertSame('dummy', $result->data['_dummy']);
    }
}