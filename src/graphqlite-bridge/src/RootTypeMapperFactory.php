<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge;

use TheCodingMachine\GraphQLite\Mappers\Root\RootTypeMapperFactoryContext;
use TheCodingMachine\GraphQLite\Mappers\Root\RootTypeMapperFactoryInterface;
use TheCodingMachine\GraphQLite\Mappers\Root\RootTypeMapperInterface;

final class RootTypeMapperFactory implements RootTypeMapperFactoryInterface
{
    public function create(
        RootTypeMapperInterface $next,
        RootTypeMapperFactoryContext $context
    ): RootTypeMapperInterface {
        return new RootTypeMapper($next);
    }
}
