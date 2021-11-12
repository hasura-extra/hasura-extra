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
    #[Query(name: 'arg_entity_test')]
    #[ArgEntity(for: 'events')]
    public function __invoke(Account $events): string
    {
        return $events->getName();
    }
}