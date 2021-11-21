<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests\Integration\GraphQLite;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

final class ObjectAssertionTest extends TestCase
{
    public function testBeforeMode(): void
    {
        $query = /** @lang GraphQL */
            <<<GQL
mutation {
object_assertion_before_test (
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

        $this->execute($query);
        $this->assertResponseIsSuccessful();

        $data = $this->responseData();

        $this->assertSame(
            [
                'object_assertion_before_test' => [
                    'emailField' => 'test@example.org',
                    'textField' => '1',
                    'sub' => [
                        'subTextField' => '2',
                    ],
                ],
            ],
            $data['data']
        );
    }

    public function testBeforeModeWithErrors(): void
    {
        $query = /** @lang GraphQL */
            <<<GQL
mutation {
object_assertion_before_test (
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

        $this->execute($query);
        $this->assertResponseStatusCodeSame(400);

        $data = $this->responseData();

        $this->assertArrayHasKey('errors', $data);

        $this->assertSame(Email::INVALID_FORMAT_ERROR, $data['errors'][0]['extensions']['code']);
        $this->assertSame('object_input.email_field', $data['errors'][0]['extensions']['field']);

        $this->assertSame(NotBlank::IS_BLANK_ERROR, $data['errors'][1]['extensions']['code']);
        $this->assertSame('text_field', $data['errors'][1]['extensions']['field']);

        $this->assertSame(NotBlank::IS_BLANK_ERROR, $data['errors'][2]['extensions']['code']);
        $this->assertSame('object_input.sub.sub_text_field', $data['errors'][2]['extensions']['field']);
    }

    public function testAfterMode(): void
    {
        $query = /** @lang GraphQL */
            <<<'GQL'
mutation Test($id: ID!) {
object_assertion_after_test (id: $id)
}
GQL;

        $this->execute($query, [
            'id' => 1,
        ]);
        $this->assertResponseIsSuccessful();

        $data = $this->responseData();

        $this->assertSame('email1@example.org', $data['data']['object_assertion_after_test']);
    }

    public function testAfterModeWithErrors(): void
    {
        $query = /** @lang GraphQL */
            <<<'GQL'
mutation Test($id: ID!) {
object_assertion_after_test (id: $id, new_email: "email2@example.org")
}
GQL;

        $this->execute($query, [
            'id' => 1,
        ]);
        $this->assertResponseStatusCodeSame(400);

        $data = $this->responseData();

        $this->assertArrayHasKey('errors', $data);
        $this->assertSame(UniqueEntity::NOT_UNIQUE_ERROR, $data['errors'][0]['extensions']['code']);
    }
}
