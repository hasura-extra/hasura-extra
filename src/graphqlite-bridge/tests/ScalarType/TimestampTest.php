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

final class TimestampTest extends TestCase
{
    public function testParseValue(): void
    {
        $result = GraphQL::executeQuery(
            $this->schema,
            'query { test_scalar(timestamp: "2021-11-05T08:17:55") { timestamp } }'
        )->toArray();

        $this->assertArrayNotHasKey('errors', $result);
        $this->assertSame('2021-11-05T08:17:55', $result['data']['test_scalar']['timestamp']);
    }

    public function testParseInvalidValue(): void
    {
        $result = GraphQL::executeQuery(
            $this->schema,
            'query { test_scalar(timestamp: "2021-11-05T08:17:55+0000") { timestamp } }'
        )->toArray();

        $this->assertArrayHasKey('errors', $result);
    }
}
