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

final class DateTest extends TestCase
{
    public function testParseValue(): void
    {
        $result = GraphQL::executeQuery(
            $this->schema,
            'query { test_scalar(date: "2021-11-05") { date } }'
        )->toArray();

        $this->assertArrayNotHasKey('errors', $result);
        $this->assertSame('2021-11-05', $result['data']['test_scalar']['date']);
    }

    public function testParseInvalidValue(): void
    {
        $result = GraphQL::executeQuery(
            $this->schema,
            'query { test_scalar(date: "2021-11-0a") { date } }'
        )->toArray();

        $this->assertArrayHasKey('errors', $result);
    }
}