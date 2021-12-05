<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\Tests\Fixture\App\Http\GraphQL\Query;

use Hasura\Laravel\GraphQLite\Attribute\ArgModel;
use Hasura\Laravel\Tests\Fixture\App\Models\Account;
use TheCodingMachine\GraphQLite\Annotations\Query;

final class ArgModelTest
{
    #[Query(name: 'arg_model_combine_test', outputType: 'json!')]
    #[ArgModel(for: 'account')]
    #[ArgModel(for: 'accountEmail', argName: 'email', fieldName: 'email', inputType: 'String!')]
    public function combine(
        Account $account,
        Account $accountEmail
    ): array {
        return [$account->id, $accountEmail->email];
    }

    #[Query(name: 'arg_model_n_plus_one_test')]
    #[ArgModel(for: 'account')]
    public function nPlusOne(
        Account $account
    ): int {
        return $account->id;
    }
}
