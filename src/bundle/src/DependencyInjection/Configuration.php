<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\DependencyInjection;

use Symfony\Bundle\MakerBundle\MakerBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $builder = new TreeBuilder('hasura');
        $root = $builder->getRootNode();
        $root
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('base_uri')
                    ->cannotBeEmpty()
                    ->defaultValue('http://hasura:8080')
                    ->info('Hasura base uri')
                ->end()
                ->scalarNode('admin_secret')
                    ->cannotBeEmpty()
                    ->defaultNull()
                    ->info('Hasura admin secret')
                ->end()
                ->scalarNode('remote_schema_name')
                    ->cannotBeEmpty()
                    ->defaultNull()
                    ->info('Application remote schema name had added on Hasura.')
                ->end()
                ->booleanNode('decorate_make_entity')
                    ->defaultValue(class_exists(MakerBundle::class))
                    ->info('Decorate to add GraphQLite resolvers base on made entity')
                ->end()
                ->arrayNode('metadata')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('state_processors')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->booleanNode('enabled_remote_schema_permissions')
                                    ->defaultTrue()
                                    ->info('Whether process GraphQLite roles attributes state to Hasura or not.')
                                ->end()
                                ->booleanNode('enabled_inherited_roles')
                                    ->defaultValue(class_exists(SecurityBundle::class))
                                    ->info('Whether process Symfony security hierarchy roles state to Hasura or not.')
                                ->end()
                            ->end()
                        ->end()
                        ->scalarNode('path')
                            ->cannotBeEmpty()
                            ->defaultValue('%kernel.project_dir%/hasura/metadata')
                            ->info('Path store your Hasura metadata.')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('auth')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('anonymous_role')
                            ->cannotBeEmpty()
                            ->defaultValue('ROLE_ANONYMOUS')
                            ->info('Role for unauthenticated user.')
                        ->end()
                        ->scalarNode('default_role')
                            ->cannotBeEmpty()
                            ->defaultValue('ROLE_USER')
                            ->info('Default role for authenticated user when user not request role via `x-hasura-role` header.')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
        return $builder;
    }
}