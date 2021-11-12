<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests\Fixture\App\GraphQL\Query;

use TheCodingMachine\GraphQLite\Annotations\Query;

final class AvoidExplicitNullTest
{
    #[Query(name: 'avoid_explicit_null_test')]
    public function __invoke(?string $arg = null): string
    {
        return 'test';
    }
}