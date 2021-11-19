<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests\DependencyInjection\CompilerPass;

use Hasura\Bundle\DependencyInjection\CompilerPass\MetadataPass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Argument\AbstractArgument;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class MetadataPassTest extends TestCase
{
    public function testMissingSecurityBundle(): void
    {
        $this->expectException(\LogicException::class);

        $pass = new MetadataPass();
        $container = new ContainerBuilder();

        $container->setParameter('hasura.metadata.state_processors.enabled_inherited_roles', true);

        $pass->process($container);
    }

    public function testProcess(): void
    {
        $pass = new MetadataPass();
        $container = new ContainerBuilder();

        $definition = $container->register('hasura.metadata.inherited_roles_state_processor');

        $definition->setArguments([new AbstractArgument()]);

        $container->setParameter('security.role_hierarchy.roles', []);
        $container->setParameter('hasura.metadata.state_processors.enabled_inherited_roles', true);

        $pass->process($container);

        $this->assertSame('%security.role_hierarchy.roles%', $definition->getArgument(0));
    }
}