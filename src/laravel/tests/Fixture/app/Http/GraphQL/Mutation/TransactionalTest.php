<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\Tests\Fixture\App\Http\GraphQL\Mutation;

use Hasura\GraphQLiteBridge\Attribute\ArgNaming;
use Hasura\Laravel\GraphQLite\Attribute\ArgModel;
use Hasura\Laravel\GraphQLite\Attribute\Transactional;
use Hasura\Laravel\Tests\Fixture\App\Models\Account;
use TheCodingMachine\GraphQLite\Annotations\Mutation;
use TheCodingMachine\GraphQLite\Exceptions\GraphQLException;

final class TransactionalTest
{
    #[Mutation(name: 'transactional_test', outputType: 'transactional_output!')]
    #[ArgModel(for: 'account')]
    #[ArgNaming(for: 'throwException', name: 'throw_exception')]
    #[Transactional]
    public function __invoke(
        Account $account,
        bool $throwException = false
    ): Account {
        $account->name = 'changed';

        $account->save();

        if ($throwException) {
            throw new GraphQLException('Dummy!');
        }

        return $account;
    }
}
