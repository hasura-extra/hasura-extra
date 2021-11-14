<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests\DependencyInjection\CompilerPass;

use Hasura\Bundle\DependencyInjection\CompilerPass\GraphQLiteMiddlewarePass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use TheCodingMachine\GraphQLite\SchemaFactory;

final class GraphQLiteMiddlewarePassTest extends TestCase
{
    public function testPass(): void
    {
        $container = new ContainerBuilder();
        $schemaFactory = $container->register(SchemaFactory::class);

        $pass = new GraphQLiteMiddlewarePass(['a'], ['b']);
        $pass->process($container);

        $methodCalls = $schemaFactory->getMethodCalls();

        $this->assertSame(2, count($methodCalls));

        $this->assertSame('addParameterMiddleware', $methodCalls[0][0]);
        $this->assertSame('a', (string)$methodCalls[0][1][0]);

        $this->assertSame('addFieldMiddleware', $methodCalls[1][0]);
        $this->assertSame('b', (string)$methodCalls[1][1][0]);
    }
}