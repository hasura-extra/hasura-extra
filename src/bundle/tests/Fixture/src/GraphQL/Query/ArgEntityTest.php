<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests\Fixture\App\GraphQL\Query;

use Hasura\Bundle\GraphQLite\Attribute\ArgEntity;
use Hasura\Bundle\Tests\Fixture\App\Entity\Account;
use TheCodingMachine\GraphQLite\Annotations\Query;

final class ArgEntityTest
{
    #[Query(name: 'arg_entity_combine_test', outputType: 'json!')]
    #[ArgEntity(for: 'account')]
    #[ArgEntity(for: 'accountEmail', argName: 'email', fieldName: 'email', inputType: 'String!')]
    public function combine(
        Account $account,
        Account $accountEmail
    ): array {
        return [$account->getId(), $accountEmail->getEmail()];
    }

    #[Query(name: 'arg_entity_n_plus_one_test')]
    #[ArgEntity(for: 'account')]
    public function nPlusOne(
        Account $account
    ): int {
        return $account->getId();
    }
}