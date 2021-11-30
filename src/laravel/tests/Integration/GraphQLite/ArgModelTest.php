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

final class ArgModelTest extends TestCase
{
    public function testCombine(): void
    {
        $query = /** @lang GraphQL */
            <<<GQL
query {
    entities: arg_model_combine_test(id: 1, email: "email2@example.org")
}
GQL;
        $response = $this->graphql($query);

        $response->assertSuccessful();

        $response->assertExactJson(
            [
                'data' => [
                    'entities' => [1, 'email2@example.org']
                ]
            ]
        );
    }

    public function testNPlusOne(): void
    {
        $this->app['db']->enableQueryLog();

        $query = /** @lang GraphQL */
            <<<GQL
query {
    m1: arg_model_n_plus_one_test(id: 1)
    m2: arg_model_n_plus_one_test(id: 2)
    m3: arg_model_n_plus_one_test(id: 3)
}
GQL;

        $response = $this->graphql($query);

        $response->assertSuccessful();
        $response->assertExactJson(
            [
                'data' => [
                    'm1' => 1,
                    'm2' => 2,
                    'm3' => 3,
                ]
            ]
        );

        // ensure n+1
        $this->assertSame(1, count($this->app['db']->getQueryLog()));
    }
}
