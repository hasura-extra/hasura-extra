<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge\RemoteSchemaProcessor;

use TheCodingMachine\GraphQLite\Annotations\Query;

final class PermissionDummyQuery
{
    public const NAME = '_dummy';

    #[Query(name: self::NAME)]
    public function __invoke(): string
    {
        return 'dummy';
    }
}