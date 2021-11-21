<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge\Tests;

use Hasura\GraphQLiteBridge\RootTypeMapper;
use Hasura\GraphQLiteBridge\RootTypeMapperFactory;
use TheCodingMachine\GraphQLite\Mappers\Root\RootTypeMapperFactoryContext;
use TheCodingMachine\GraphQLite\Mappers\Root\RootTypeMapperInterface;

final class RootTypeMapperFactoryTest extends TestCase
{
    public function testFactory(): void
    {
        $factory = new RootTypeMapperFactory();

        $mapper = $factory->create(
            $this->createMock(RootTypeMapperInterface::class),
            (new \ReflectionClass(RootTypeMapperFactoryContext::class))->newInstanceWithoutConstructor()
        );

        $this->assertInstanceOf(RootTypeMapper::class, $mapper);
    }
}
