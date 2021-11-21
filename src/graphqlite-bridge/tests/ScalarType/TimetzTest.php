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

final class TimetzTest extends TestCase
{
    public function testParseValue(): void
    {
        $result = GraphQL::executeQuery(
            $this->schema,
            'query { test_scalar(timetz: "08:17:55+0000") { timetz } }'
        )->toArray();

        $this->assertArrayNotHasKey('errors', $result);
        $this->assertSame('08:17:55+0000', $result['data']['test_scalar']['timetz']);
    }

    public function testParseInvalidValue(): void
    {
        $result = GraphQL::executeQuery(
            $this->schema,
            'query { test_scalar(timetz: "2021-11-05T08:17:55+0000") { timetz } }'
        )->toArray();

        $this->assertArrayHasKey('errors', $result);
    }
}
