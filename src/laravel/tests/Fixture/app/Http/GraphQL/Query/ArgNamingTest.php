<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\Tests\Fixture\App\Http\GraphQL\Query;

use Hasura\GraphQLiteBridge\Attribute\ArgNaming;
use TheCodingMachine\GraphQLite\Annotations\Query;

final class ArgNamingTest
{
    #[Query(name: 'arg_naming_tests')]
    #[ArgNaming(for: 'camelCase', name: 'snake_case')]
    public function __invoke(
        string $camelCase
    ): string {
        return 'input: ' . $camelCase;
    }
}
