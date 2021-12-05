<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\Tests\Integration\GraphQLite;

use Hasura\Laravel\Tests\TestCase;

final class ArgNamingTest extends TestCase
{
    public function testNaming(): void
    {
        $query = /** @lang GraphQL */
            <<<GQL
query {
    arg_naming_tests(snake_case: "test")
}
GQL;
        $response = $this->graphql($query);

        $response->assertSuccessful();
        $response->assertJson(
            [
                'data' => [
                    'arg_naming_tests' => 'input: test',
                ],
            ],
            true
        );
    }
}
