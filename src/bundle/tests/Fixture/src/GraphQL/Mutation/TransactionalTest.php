<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests\Fixture\App\GraphQL\Mutation;

use Doctrine\ORM\EntityManagerInterface;
use Hasura\Bundle\GraphQLite\Attribute\ArgEntity;
use Hasura\Bundle\GraphQLite\Attribute\Transactional;
use Hasura\Bundle\Tests\Fixture\App\Entity\Account;
use Hasura\GraphQLiteBridge\Attribute\ArgNaming;
use TheCodingMachine\GraphQLite\Annotations\Mutation;
use TheCodingMachine\GraphQLite\Exceptions\GraphQLException;

final class TransactionalTest
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Mutation(name: 'transactional_test', outputType: 'transactional_output!')]
    #[ArgEntity(for: 'account')]
    #[ArgNaming(for: 'throwException', name: 'throw_exception')]
    #[Transactional]
    public function __invoke(
        Account $account,
        bool $throwException = false
    ): Account {
        $account->setName('changed');

        $this->entityManager->flush();

        if ($throwException) {
            throw new GraphQLException('Dummy!');
        }

        return $account;
    }
}