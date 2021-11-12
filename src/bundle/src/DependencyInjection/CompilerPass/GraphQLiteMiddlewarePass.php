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

final class GraphQLiteMiddlewarePass implements CompilerPassInterface
{
    public function __construct(private array $parameterMiddlewares = [], private array $fieldMiddlewares = [])
    {
    }

    public function process(ContainerBuilder $container)
    {
        $schemaFactory = $container->getDefinition(SchemaFactory::class);

        foreach ($this->parameterMiddlewares as $middleware) {
            $schemaFactory->addMethodCall('addParameterMiddleware', [new Reference($middleware)]);
        }

        foreach ($this->fieldMiddlewares as $middleware) {
            $schemaFactory->addMethodCall('addFieldMiddleware', [new Reference($middleware)]);
        }
    }
}