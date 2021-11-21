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
    private const PARAMETER_MIDDLEWARES = [
        'hasura.graphql.parameter.object_assertion_middleware',
        'hasura.graphql.parameter.arg_naming_middleware',
        'hasura.graphql.parameter.arg_entity_middleware',
        'hasura.graphql.parameter.avoid_explicit_default_null_middleware',
    ];

    private const FIELD_MIDDLEWARES = [
        'hasura.graphql.field.object_assertion_middleware',
        'hasura.graphql.field.annotation_tracking_middleware',
        'hasura.graphql.field.arg_naming_middleware',
        'hasura.graphql.field.transactional_middleware',
        'hasura.graphql.field.authorization_middleware',
    ];

    public function process(ContainerBuilder $container)
    {
        $schemaFactory = $container->getDefinition(SchemaFactory::class);

        foreach (self::PARAMETER_MIDDLEWARES as $middleware) {
            $schemaFactory->addMethodCall('addParameterMiddleware', [new Reference($middleware)]);
        }

        foreach (self::FIELD_MIDDLEWARES as $middleware) {
            $schemaFactory->addMethodCall('addFieldMiddleware', [new Reference($middleware)]);
        }

        $container->setAlias(AssertParameterMiddleware::class, 'hasura.graphql.parameter.assertion_middleware');
        $container->setAlias(AuthorizationServiceInterface::class, 'hasura.graphql.authorization_service');
    }
}
