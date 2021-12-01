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

final class ValidateTest extends TestCase
{
    public function testNaming(): void
    {
        $query = /** @lang GraphQL */
            <<<GQL
mutation {
    validate_test(email_naming: "test@example.org")
}
GQL;
        $response = $this->graphql($query);

        $response->assertSuccessful();
        $response->assertExactJson(
            [
                'data' => [
                    'validate_test' => 'assertion: test@example.org'
                ]
            ]
        );
    }

    public function testNamingWithError(): void
    {
        $query = /** @lang GraphQL */
            <<<GQL
mutation {
    validate_test(email_naming: "error")
}
GQL;
        $response = $this->graphql($query);

        $response->assertStatus(400);
        $response->assertJson(
            [
                'errors' => [
                    [
                        'extensions' => [
                            'category' => 'Validate',
                            'argument' => 'email_naming'
                        ]
                    ]
                ]
            ]
        );
    }
}
