<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests\Integration\GraphQLite;

final class RolesTest extends TestCase
{
    public function testUnauthorized(): void
    {
        $query = /** @lang GraphQL */
            <<<GQL
query {
roles_test
}
GQL;
        $this->execute($query);

        $this->assertResponseStatusCodeSame(403);

        $data = $this->responseData();

        $this->assertArrayHasKey('errors', $data);
        $this->assertSame('security', $data['errors'][0]['extensions']['category']);
    }

    public function testAuthorized(): void
    {
        $query = /** @lang GraphQL */
            <<<GQL
query {
roles_test
}
GQL;
        $this->execute(
            $query,
            server: [
                'HTTP_AUTHORIZATION' => 'Basic ' . base64_encode('test:test'),
            ]
        );

        $this->assertResponseIsSuccessful();

        $data = $this->responseData();

        $this->assertSame('authorized', $data['data']['roles_test']);
    }
}
