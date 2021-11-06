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

final class JsonTest extends TestCase
{
    public function testParseValue(): void
    {
        $result = GraphQL::executeQuery(
            $this->schema,
            'query { test_scalar(json: [1, 2, 3]) { json } }'
        )->toArray();

        $this->assertArrayNotHasKey('errors', $result);
        $this->assertSame([1, 2, 3], $result['data']['test_scalar']['json']);
    }

    public function testParseInvalidValue(): void
    {
        $result = GraphQL::executeQuery(
            $this->schema,
            'query { test_scalar(json: "2021-11-01") { json } }'
        )->toArray();

        $this->assertArrayHasKey('errors', $result);
    }
}