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

final class ValidateObjectTest extends TestCase
{
    public function testAssertion(): void
    {
        $query = /** @lang GraphQL */
            <<<GQL
mutation {
validate_object_test (
    object_input: {
        email_field: "test@example.org", 
        text_field: "1", 
        sub: { 
            sub_text_field: "2" 
        } 
    }
)
}
GQL;

        $response = $this->graphql($query);
        $response->assertSuccessful();

        $response->assertExactJson(
            [
                'data' => [
                    'validate_object_test' => [
                        'emailField' => 'test@example.org',
                        'textField' => '1',
                        'sub' => [
                            'subTextField' => '2',
                        ],
                    ],
                ],
            ]
        );
    }

    public function testAssertionErrors(): void
    {
        $query = /** @lang GraphQL */
            <<<GQL
mutation {
validate_object_test (
    object_input: {
        email_field: "error", 
        text_field: "", 
        sub: { 
            sub_text_field: "" 
        } 
    }
)
}
GQL;

        $response = $this->graphql($query);

        $response->assertStatus(400);
        $response->assertJson(
            [
                'errors' => [
                    [
                        'extensions' => [
                            'argument' => 'object_input.email_field',
                            'category' => 'Validate',
                        ],
                    ],
                    [
                        'extensions' => [
                            'argument' => 'text_field',
                            'category' => 'Validate',
                        ],
                    ],
                    [
                        'extensions' => [
                            'argument' => 'object_input.sub.sub_text_field',
                            'category' => 'Validate',
                        ],
                    ],
                ],
            ]
        );
    }
}
