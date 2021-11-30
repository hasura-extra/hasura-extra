<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\Tests\Integration\GraphQLite;

use Hasura\Laravel\Tests\Fixture\App\Models\Account;
use Hasura\Laravel\Tests\TestCase;

final class TransactionalTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        $accountTest = Account::find(1);
        $accountTest->name = 'Test 1';
        $accountTest->saveOrFail();

        parent::tearDown();
    }

    public function testTransactional()
    {
        $query = /** @lang GraphQL */
            <<<GQL
mutation {
transactional_test (id: 1) { name }
}
GQL;
        $response = $this->graphql($query);

        $response->assertSuccessful();

        $data = json_decode($response->getContent(), true);
        $account = Account::find(1);

        $this->assertSame('changed', $account->name);
        $this->assertSame($account->name, $data['data']['transactional_test']['name']);
    }

    public function testTransactionalWithErrors()
    {
        $query = /** @lang GraphQL */
            <<<GQL
mutation {
transactional_test (id: 1, throw_exception: true) { name }
}
GQL;
        $response = $this->graphql($query);

        $response->assertStatus(400);

        $this->assertSame('Test 1', Account::find(1)->refresh()->name);
    }
}
