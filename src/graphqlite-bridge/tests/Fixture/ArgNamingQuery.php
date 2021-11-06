<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge\Tests\Fixture;

use Hasura\GraphQLiteBridge\Attribute\ArgNaming;
use TheCodingMachine\GraphQLite\Annotations\Query;

final class ArgNamingQuery
{
    #[Query(name: 'arg_naming', outputType: 'json!')]
    #[ArgNaming(for: 'camelCase', name: 'snake_case')]
    #[ArgNaming(for: 'camelCase2', name: 'snake_case_2')]
    public function __invoke(
        string $camelCase,
        int $camelCase2
    ): array {
        return [$camelCase, $camelCase2];
    }
}