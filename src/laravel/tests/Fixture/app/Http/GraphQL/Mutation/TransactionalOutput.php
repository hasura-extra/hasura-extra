<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\Tests\Fixture\App\Http\GraphQL\Mutation;

use Hasura\Laravel\Tests\Fixture\App\Models\Account;
use TheCodingMachine\GraphQLite\Annotations\MagicField;
use TheCodingMachine\GraphQLite\Annotations\Type;

#[Type(class: Account::class, name: 'transactional_output')]
#[MagicField(name: 'name', outputType: 'String!')]
final class TransactionalOutput
{
}