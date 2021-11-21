<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge\Tests\ScalarType;

use GraphQL\GraphQL;
use Hasura\GraphQLiteBridge\Tests\TestCase;

final class UuidTest extends TestCase
{
    public function testParseValue(): void
    {
        $result = GraphQL::executeQuery(
            $this->schema,
            'query { test_scalar(uuid: "12abb417-0b10-4fbd-84c1-4bf2ab5e63ec") { uuid } }'
        )->toArray();

        $this->assertArrayNotHasKey('errors', $result);
        $this->assertSame('12abb417-0b10-4fbd-84c1-4bf2ab5e63ec', $result['data']['test_scalar']['uuid']);
    }

    public function testParseInvalidValue(): void
    {
        $result = GraphQL::executeQuery(
            $this->schema,
            'query { test_scalar(uuid: "") { uuid } }'
        )->toArray();

        $this->assertArrayHasKey('errors', $result);
    }
}
