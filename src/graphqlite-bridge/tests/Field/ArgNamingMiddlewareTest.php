<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge\Tests\Field;

use GraphQL\GraphQL;
use GraphQL\Utils\SchemaPrinter;
use Hasura\GraphQLiteBridge\Tests\TestCase;

final class ArgNamingMiddlewareTest extends TestCase
{
    public function testCanNaming(): void
    {
        $sdl = SchemaPrinter::doPrint($this->schema);

        $this->assertStringContainsString('arg_naming(snake_case: String!, snake_case_2: Int!)', $sdl);

        $result = GraphQL::executeQuery(
            $this->schema,
            'query { arg_naming(snake_case: "a", snake_case_2: 1) }'
        )->toArray();

        $this->assertArrayNotHasKey('errors', $result);
        $this->assertSame(['a', 1], $result['data']['arg_naming']);
    }
}
