<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests\DependencyInjection\CompilerPass;

use Hasura\Bundle\DependencyInjection\CompilerPass\DecorateMakeEntityPass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class DecorateMakeEntityPassTest extends TestCase
{
    public function testProcess(): void
    {
        $pass = new DecorateMakeEntityPass();
        $container = new ContainerBuilder();

        $container->setParameter('hasura.maker.decorate_make_entity', true);
        $container->register('hasura.maker.make_entity');
        $container->register('maker.maker.make_entity');

        $pass->process($container);

        $this->assertTrue($container->hasDefinition('hasura.maker.make_entity'));
    }

    public function testMakeEntityNotExists(): void
    {
        $pass = new DecorateMakeEntityPass();
        $container = new ContainerBuilder();

        $container->setParameter('hasura.maker.decorate_make_entity', true);
        $container->register('hasura.maker.make_entity');

        $pass->process($container);

        $this->assertFalse($container->hasDefinition('hasura.maker.make_entity'));
    }

    public function testDisableDecorateMakeEntity(): void
    {
        $pass = new DecorateMakeEntityPass();
        $container = new ContainerBuilder();

        $container->setParameter('hasura.maker.decorate_make_entity', false);
        $container->register('hasura.maker.make_entity');
        $container->register('maker.maker.make_entity');

        $pass->process($container);

        $this->assertFalse($container->hasDefinition('hasura.maker.make_entity'));
    }
}