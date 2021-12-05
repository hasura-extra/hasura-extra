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

final class RolesTest extends TestCase
{
    public function testAnonymous()
    {
        $query = /** @lang GraphQL */
            <<<GQL
query {
    anonymous_roles_test
}
GQL;
        $response = $this->graphql($query);

        $response->assertSuccessful();
        $response->assertExactJson([
            'data' => [
                'anonymous_roles_test' => 'anonymous',
                
            ],
        ]);
    }

    public function testUnauthorized()
    {
        $query = /** @lang GraphQL */
            <<<GQL
query {
    roles_test
}
GQL;
        $response = $this->graphql($query);
        $response->assertForbidden();
    }

    public function testAuthenticated()
    {
        $this->loginWithRoles('tester');

        $query = /** @lang GraphQL */
            <<<GQL
query {
    roles_test
}
GQL;
        $response = $this->graphql($query);

        $response->assertSuccessful();
        $response->assertExactJson([
            'data' => [
                'roles_test' => 'authorized',
                
            ],
        ]);
    }
}
