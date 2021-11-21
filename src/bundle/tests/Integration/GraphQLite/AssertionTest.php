<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests\Integration\GraphQLite;

use Symfony\Component\Validator\Constraints\Email;

final class AssertionTest extends TestCase
{
    public function testNaming(): void
    {
        $query = /** @lang GraphQL */
            <<<GQL
mutation {
    assertion_test(email_naming: "test@example.org")
}
GQL;
        $this->execute($query);

        $this->assertResponseIsSuccessful();
        $data = $this->responseData();

        $this->assertSame('assertion: test@example.org', $data['data']['assertion_test']);
    }

    public function testNamingWithError(): void
    {
        $query = /** @lang GraphQL */
            <<<GQL
mutation {
    assertion_test(email_naming: "error")
}
GQL;
        $this->execute($query);

        $this->assertResponseStatusCodeSame(400);

        $data = $this->responseData();

        $this->assertArrayHasKey('errors', $data);
        $this->assertSame('email_naming', $data['errors'][0]['extensions']['field']);
        $this->assertSame(Email::INVALID_FORMAT_ERROR, $data['errors'][0]['extensions']['code']);
    }
}
