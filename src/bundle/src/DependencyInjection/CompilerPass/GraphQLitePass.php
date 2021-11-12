<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use TheCodingMachine\GraphQLite\SchemaFactory;
use TheCodingMachine\GraphQLite\Security\AuthorizationServiceInterface;
use TheCodingMachine\GraphQLite\Validator\Mappers\Parameters\AssertParameterMiddleware;

final class GraphQLitePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $schemaFactory = $container->getDefinition(SchemaFactory::class);

        $schemaFactory->addMethodCall('addControllerNamespace', ['Hasura\GraphQLiteBridge\Controller']);
        $schemaFactory->addMethodCall(
            'addFieldMiddleware',
            [
                new Reference('hasura.graphql.field.authorization_middleware')
            ]
        );

        $container->removeDefinition(AssertParameterMiddleware::class);

        $container->setAlias(AssertParameterMiddleware::class, 'hasura.graphql.parameter.assertion_middleware');
        $container->setAlias(AuthorizationServiceInterface::class, 'hasura.graphql.authorization_service');
    }
}