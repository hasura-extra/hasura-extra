<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\Tests\Fixture\App\Http\GraphQL\Query;

use TheCodingMachine\GraphQLite\Annotations\Query;

final class Test
{
    #[Query(name: 'test')]
    public function __invoke(): string
    {
        return 'test';
    }
}