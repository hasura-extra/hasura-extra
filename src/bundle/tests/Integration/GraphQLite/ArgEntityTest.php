<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests\Integration\GraphQLite;

use Doctrine\DBAL\Logging\DebugStack;

final class ArgEntityTest extends TestCase
{
    public function testCombine(): void
    {
        $query = /** @lang GraphQL */ <<<GQL
query {
    entities: arg_entity_combine_test(id: 1, email: "email2@example.org")
}
GQL;
        $this->execute($query);

        $this->assertResponseIsSuccessful();

        $data = $this->responseData();

        $this->assertSame([1, 'email2@example.org'], $data['data']['entities']);
    }

    public function testNPlusOne(): void
    {
        $query = /** @lang GraphQL */ <<<GQL
query {
    e1: arg_entity_n_plus_one_test(id: 1)
    e2: arg_entity_n_plus_one_test(id: 2)
    e3: arg_entity_n_plus_one_test(id: 3)
}
GQL;

        $this->execute($query);

        $this->assertResponseIsSuccessful();

        $data = $this->responseData();

        $this->assertSame(1, $data['data']['e1']);
        $this->assertSame(2, $data['data']['e2']);
        $this->assertSame(3, $data['data']['e3']);

        // ensure n+1
        /** @var DebugStack $profile */
        $profile = $this->client->getContainer()->get('doctrine.dbal.logger.profiling.default');

        $this->assertSame(1, count($profile->queries));
    }
}
