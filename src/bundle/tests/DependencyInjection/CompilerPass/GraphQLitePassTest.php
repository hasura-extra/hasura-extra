<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests\DependencyInjection\CompilerPass;

use Hasura\Bundle\DependencyInjection\CompilerPass\GraphQLitePass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use TheCodingMachine\GraphQLite\SchemaFactory;
use TheCodingMachine\GraphQLite\Security\AuthorizationServiceInterface;
use TheCodingMachine\GraphQLite\Validator\Mappers\Parameters\AssertParameterMiddleware;

final class GraphQLitePassTest extends TestCase
{
    public function testPass(): void
    {
        $pass = new GraphQLitePass();
        $container = new ContainerBuilder();

        $schemaFactory = $container->register(SchemaFactory::class);

        $pass->process($container);

        $methodCalls = $schemaFactory->getMethodCalls();

        $this->assertNotEmpty($methodCalls);
        $this->assertTrue($container->hasAlias(AuthorizationServiceInterface::class));
        $this->assertTrue($container->hasAlias(AssertParameterMiddleware::class));

        $this->assertSame(
            'hasura.graphql.authorization_service',
            (string)$container->getAlias(AuthorizationServiceInterface::class)
        );

        $this->assertSame(
            'hasura.graphql.parameter.assertion_middleware',
            (string)$container->getAlias(AssertParameterMiddleware::class)
        );
    }
}
