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
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

final class HasuraExtension extends Extension implements PrependExtensionInterface
{
    public function prepend(ContainerBuilder $container): void
    {
        if (isset($container->getExtensions()['security'])) {
            $container->prependExtensionConfig(
                'security',
                [
                    'enable_authenticator_manager' => true
                ]
            );
        }
    }

    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $loader->load('psr_http_message.php');

        $this->prepareContainerParameters($container, $config);

        $this->registerApiClient($container, $config, $loader);
        $this->registerAuthHook($container, $config, $loader);
        $this->registerMetadata($container, $config, $loader);
    }

    private function prepareContainerParameters(ContainerBuilder $containerBuilder, array $config): void
    {
        $containerBuilder->setParameter('hasura.base_uri', $config['base_uri']);
        $containerBuilder->setParameter('hasura.auth_hook.default_role', $config['auth_hook']['default_role']);
        $containerBuilder->setParameter('hasura.auth_hook.anonymous_role', $config['auth_hook']['anonymous_role']);
        $containerBuilder->setParameter('hasura.metadata.path', $config['metadata']['path']);
        $containerBuilder->setParameter('hasura.sailor.executor_path', $config['sailor']['executor_path']);
        $containerBuilder->setParameter('hasura.sailor.executor_namespace', $config['sailor']['executor_namespace']);
        $containerBuilder->setParameter('hasura.sailor.query_spec_path', $config['sailor']['query_spec_path']);
        $containerBuilder->setParameter('hasura.sailor.schema_path', $config['sailor']['schema_path']);
    }

    private function registerApiClient(ContainerBuilder $containerBuilder, array $config, PhpFileLoader $loader): void
    {
        $loader->load('api_client.php');
        $definition = $containerBuilder->getDefinition('hasura.api_client.client');
        $definition->replaceArgument(1, $config['admin_secret']);
    }

    private function registerAuthHook(ContainerBuilder $containerBuilder, array $config, PhpFileLoader $loader): void
    {
        $loader->load('auth_hook.php');

        $containerBuilder
            ->registerForAutoconfiguration(SessionVariableEnhancerInterface::class)
            ->addTag('hasura.auth_hook.session_variable_enhancer');
    }

    private function registerMetadata(ContainerBuilder $containerBuilder, array $config, PhpFileLoader $loader): void
    {
        $loader->load('metadata.php');

        $containerBuilder
            ->registerForAutoconfiguration(StateProcessorInterface::class)
            ->addTag('hasura.metadata.state_processor');
    }
}