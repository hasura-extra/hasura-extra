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

final class DecorateMakeEntityPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (
            false === $container->hasDefinition('maker.maker.make_entity')
            || false === $container->getParameter('hasura.maker.decorate_make_entity')
        ) {
            $container->removeDefinition('hasura.maker.make_entity');
        }
    }
}
