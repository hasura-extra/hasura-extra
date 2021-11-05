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

final class JsonbTest extends TestCase
{
    public function testParseValue(): void
    {
        $result = GraphQL::executeQuery(
            $this->schema,
            'query { scalar(jsonb: { a: 1, b: false, c: [1, 2, 3], d: "e", f: {} }) { jsonb } }'
        )->toArray();

        $this->assertArrayNotHasKey('errors', $result);
        $this->assertSame(
            ['a' => 1, 'b' => false, 'c' => [1, 2, 3], 'd' => 'e', 'f' => []],
            $result['data']['scalar']['jsonb']
        );
    }

    public function testParseInvalidValue(): void
    {
        $result = GraphQL::executeQuery(
            $this->schema,
            'query { scalar(jsonb: true) { jsonb } }'
        )->toArray();

        $this->assertArrayHasKey('errors', $result);
    }
}