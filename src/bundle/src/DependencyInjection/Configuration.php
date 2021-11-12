<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\DependencyInjection;

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
                ->end()
                ->scalarNode('admin_secret')
                    ->cannotBeEmpty()
                    ->defaultNull()
                ->end()
                ->scalarNode('remote_schema_name')
                    ->cannotBeEmpty()
                    ->defaultNull()
                ->end()
                ->arrayNode('metadata')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('path')
                            ->cannotBeEmpty()
                            ->defaultValue('%kernel.project_dir%/hasura/metadata')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('auth')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('anonymous_role')
                            ->cannotBeEmpty()
                            ->defaultValue('ROLE_ANONYMOUS')
                        ->end()
                        ->scalarNode('default_role')
                            ->cannotBeEmpty()
                            ->defaultValue('ROLE_USER')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('sailor')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('executor_namespace')
                            ->cannotBeEmpty()
                            ->defaultValue('App\GraphQLExecutor')
                        ->end()
                        ->scalarNode('executor_path')
                            ->cannotBeEmpty()
                            ->defaultValue('%kernel.project_dir%/src/GraphQLExecutor')
                        ->end()
                        ->scalarNode('query_spec_path')
                            ->cannotBeEmpty()
                            ->defaultValue('%kernel.project_dir%/hasura/graphql')
                        ->end()
                        ->scalarNode('schema_path')
                            ->cannotBeEmpty()
                            ->defaultValue('%kernel.project_dir%/hasura/schema.graphql')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
        return $builder;
    }
}