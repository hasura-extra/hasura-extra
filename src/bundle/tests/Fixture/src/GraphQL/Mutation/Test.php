<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests\Fixture\App\GraphQL\Mutation;

use TheCodingMachine\GraphQLite\Annotations\Mutation;

final class Test
{
    #[Mutation(name: 'test')]
    public function __invoke(): string
    {
        return 'test';
    }
}