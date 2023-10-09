<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\DependencyInjection;

use Hasura\AuthHook\SessionVariableEnhancerInterface;
use Hasura\Metadata\StateProcessorInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

final class HasuraExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $loader->load('psr_http_message.php');
        $loader->load('event_dispatcher.php');

        $container->setParameter('hasura.base_uri', $config['base_uri']);

        $this->registerApiClient($container, $config, $loader);
        $this->registerAuth($container, $config['auth'], $loader);
        $this->registerMetadata($container, $config['metadata'], $loader);

        $this->configRemoteSchema($container, $config);
    }

    private function registerApiClient(ContainerBuilder $container, array $config, PhpFileLoader $loader): void
    {
        $loader->load('api_client.php');

        $client = $container->getDefinition('hasura.api_client.client');
        $client->replaceArgument(1, $config['admin_secret']);
    }

    private function registerAuth(ContainerBuilder $container, array $config, PhpFileLoader $loader): void
    {
        $container->setParameter('hasura.auth.default_role', $config['default_role']);
        $container->setParameter('hasura.auth.anonymous_role', $config['anonymous_role']);

        $loader->load('auth.php');

        $container
            ->registerForAutoconfiguration(SessionVariableEnhancerInterface::class)
            ->addTag('hasura.auth_hook.session_variable_enhancer');
    }

    private function registerMetadata(ContainerBuilder $container, array $config, PhpFileLoader $loader): void
    {
        $container->setParameter('hasura.metadata.path', $config['path']);

        $container->setParameter(
            'hasura.metadata.state_processors.enabled_inherited_roles',
            $config['state_processors']['enabled_inherited_roles']
        );

        $loader->load('metadata.php');

        if (false === $config['state_processors']['enabled_inherited_roles']) {
            $container->removeDefinition('hasura.metadata.inherited_roles_state_processor');
        }

        $container
            ->registerForAutoconfiguration(StateProcessorInterface::class)
            ->addTag('hasura.metadata.state_processor');
    }

    private function configRemoteSchema(ContainerBuilder $container, array $config): void
    {
        if (null === $config['remote_schema_name']) {
            $container->removeDefinition('hasura.metadata.remote_schema');
            return;
        }

        $remoteSchema = $container->getDefinition('hasura.metadata.remote_schema');
        $remoteSchema->replaceArgument(0, $config['remote_schema_name']);
    }
}
