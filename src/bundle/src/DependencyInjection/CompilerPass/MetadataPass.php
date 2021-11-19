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

final class MetadataPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if ($container->getParameter('hasura.metadata.state_processors.enabled_inherited_roles')) {
            if (false === $container->hasParameter('security.role_hierarchy.roles')) {
                throw new \LogicException(
                    '`hasura.metadata.state_processors.enabled_inherited_roles` enabled but the SecurityBundle is not registered in your application. Try running "composer require symfony/security-bundle" or disabled it.'
                );
            }

            $container
                ->getDefinition('hasura.metadata.inherited_roles_state_processor')
                ->replaceArgument(0, '%security.role_hierarchy.roles%');
        }
    }
}