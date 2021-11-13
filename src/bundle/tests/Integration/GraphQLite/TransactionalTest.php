<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests\Integration\GraphQLite;

use Doctrine\ORM\EntityManagerInterface;
use Hasura\Bundle\Tests\Fixture\App\Entity\Account;

final class TransactionalTest extends TestCase
{
    private ?EntityManagerInterface $em;

    protected function setUp(): void
    {
        parent::setUp();

        $this->em = $this->client->getContainer()->get('doctrine')->getManager();
        $this->em->beginTransaction();
    }

    protected function tearDown(): void
    {
        $this->em->rollback();
        $this->em->close();
        $this->em = null;

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
        $this->execute($query);

        $this->assertResponseIsSuccessful();

        $data = $this->responseData();
        $account = $this->em->find(Account::class, 1);

        $this->em->refresh($account);

        $this->assertSame('changed', $account->getName());
        $this->assertSame($account->getName(), $data['data']['transactional_test']['name']);
    }

    public function testTransactionalWithErrors()
    {
        $query = /** @lang GraphQL */
            <<<GQL
mutation {
transactional_test (id: 1, throw_exception: true) { name }
}
GQL;
        $this->execute($query);

        $this->assertResponseStatusCodeSame(400);

        $this->assertFalse($this->em->isOpen());

        $this->em = $this->client->getContainer()->get('doctrine')->resetManager();

        $account = $this->em->find(Account::class, 1);

        $this->em->refresh($account);

        $this->assertSame('Test 1', $account->getName());
    }
}