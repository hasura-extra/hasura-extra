<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests;

use Hasura\Bundle\DependencyInjection\CompilerPass\MetadataPass;
use Hasura\Bundle\HasuraBundle;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class HasuraBundleTest extends KernelTestCase
{
    public function testBuild()
    {
        $container = new ContainerBuilder();
        $bundle = new HasuraBundle();

        $bundle->build($container);

        $passConfig = $container->getCompilerPassConfig();
        $passClasses = array_map(fn (CompilerPassInterface $pass) => $pass::class, $passConfig->getPasses());

        $this->assertContains(MetadataPass::class, $passClasses);
    }
}
