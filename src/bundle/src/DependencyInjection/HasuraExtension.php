<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\DependencyInjection;

use Doctrine\Bundle\DoctrineBundle\Registry;
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
        $container->prependExtensionConfig(
            'security',
            [
                'enable_authenticator_manager' => true
            ]
        );
    }

    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $loader->load('psr_http_message.php');
        $loader->load('event_dispatcher.php');

        $container->setParameter('hasura.base_uri', $config['base_uri']);

        $this->registerApiClient($container, $config, $loader);
        $this->registerAuth($container, $config, $loader);
        $this->registerGraphQLite($container, $config, $loader);
        $this->registerMaker($container, $config, $loader);
        $this->registerMetadata($container, $config, $loader);
        $this->registerSailor($container, $config, $loader);

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
        $container->setParameter('hasura.auth.default_role', $config['auth']['default_role']);
        $container->setParameter('hasura.auth.anonymous_role', $config['auth']['anonymous_role']);

        $loader->load('auth.php');

        $container
            ->registerForAutoconfiguration(SessionVariableEnhancerInterface::class)
            ->addTag('hasura.auth_hook.session_variable_enhancer');
    }

    private function registerGraphQLite(ContainerBuilder $container, array $config, PhpFileLoader $loader): void
    {
        $loader->load('graphqlite.php');

        if (!class_exists(Registry::class)) {
            $container->removeDefinition('hasura.graphql.parameter.arg_entity_middleware');
            $container->removeDefinition('hasura.graphql.field.transactional_middleware');
        }
    }

    private function registerMaker(ContainerBuilder $container, array $config, PhpFileLoader $loader): void
    {
        $loader->load('maker.php');

        if (false === $config['decorate_make_entity']) {
            $container->removeDefinition('hasura.maker.maker_entity');
        }
    }

    private function registerMetadata(ContainerBuilder $container, array $config, PhpFileLoader $loader): void
    {
        $container->setParameter('hasura.metadata.path', $config['metadata']['path']);

        $loader->load('metadata.php');

        $container
            ->registerForAutoconfiguration(StateProcessorInterface::class)
            ->addTag('hasura.metadata.state_processor');
    }

    private function registerSailor(ContainerBuilder $containerBuilder, array $config, PhpFileLoader $loader): void
    {
        $containerBuilder->setParameter('hasura.sailor.executor_path', $config['sailor']['executor_path']);
        $containerBuilder->setParameter('hasura.sailor.executor_namespace', $config['sailor']['executor_namespace']);
        $containerBuilder->setParameter('hasura.sailor.query_spec_path', $config['sailor']['query_spec_path']);
        $containerBuilder->setParameter('hasura.sailor.schema_path', $config['sailor']['schema_path']);

        $loader->load('sailor.php');
    }

    private function configRemoteSchema(ContainerBuilder $container, array $config): void
    {
        if (null === $config['remote_schema_name']) {
            $container->removeDefinition('hasura.metadata.remote_schema');
            $container->removeDefinition('hasura.graphql.remote_schema_permission_state_processor');

            return;
        }

        $remoteSchema = $container->getDefinition('hasura.metadata.remote_schema');
        $remoteSchema->replaceArgument(0, $config['remote_schema_name']);
    }
}