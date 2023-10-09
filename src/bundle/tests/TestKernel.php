<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

final class TestKernel extends Kernel implements CompilerPassInterface
{
    use MicroKernelTrait;

    public function process(ContainerBuilder $container)
    {
        $container->getDefinition('hasura.api_client.client')->setPublic(true);
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->import('Fixture/config/packages/*.yaml');
        $container->import('Fixture/config/services.yaml');
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import('Fixture/config/routes.yaml');
    }

    public function getProjectDir(): string
    {
        return __DIR__ . '/Fixture';
    }
}
