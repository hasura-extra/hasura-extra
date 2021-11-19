<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle;

use Hasura\Bundle\DependencyInjection\CompilerPass\DecorateMakeEntityPass;
use Hasura\Bundle\DependencyInjection\CompilerPass\GraphQLitePass;
use Hasura\Bundle\DependencyInjection\CompilerPass\MetadataPass;
use Spawnia\Sailor\Configuration;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class HasuraBundle extends Bundle
{
    public function boot(): void
    {
        $sailorConfig = $this->container->get('hasura.sailor.endpoint_config');

        Configuration::setEndpoint('hasura', $sailorConfig);
    }

    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new DecorateMakeEntityPass());
        $container->addCompilerPass(new MetadataPass());
        $container->addCompilerPass(new GraphQLitePass(), priority: -2);
    }
}