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
                ->scalarNode('metadata_path')
                    ->cannotBeEmpty()
                    ->defaultValue('%kernel.project_dir%/hasura/metadata')
                ->end()
                ->scalarNode('anonymous_role')
                    ->cannotBeEmpty()
                    ->defaultValue('ROLE_ANONYMOUS')
                ->end()
                ->scalarNode('default_role')
                    ->cannotBeEmpty()
                    ->defaultValue('ROLE_USER')
                ->end()
                ->scalarNode('sailor_executor_namespace')
                    ->cannotBeEmpty()
                    ->defaultValue('App\GraphQLExecutor')
                ->end()
                ->scalarNode('sailor_query_spec_path')
                    ->cannotBeEmpty()
                    ->defaultValue('%kernel.project_dir%/hasura/graphql')
                ->end()
                ->scalarNode('sailor_schema_path')
                    ->cannotBeEmpty()
                    ->defaultValue('%kernel.project_dir%/hasura/schema.gql')
                ->end()
            ->end()
        ;
        return $builder;
    }
}