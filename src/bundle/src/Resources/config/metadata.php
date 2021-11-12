<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Hasura\Metadata\Command\ApplyMetadata;
use Hasura\Metadata\Command\BaseCommand;
use Hasura\Metadata\Command\ClearMetadata;
use Hasura\Metadata\Command\DropInconsistentMetadata;
use Hasura\Metadata\Command\ExportMetadata;
use Hasura\Metadata\Command\GetInconsistentMetadata;
use Hasura\Metadata\Command\PersistState;
use Hasura\Metadata\Command\ReloadMetadata;
use Hasura\Metadata\Manager;
use Hasura\Metadata\RemoteSchema;
use Hasura\Metadata\RemoteSchemaReloadStateProcessor;
use Hasura\Metadata\YamlOperator;

return static function (ContainerConfigurator $configurator) {
    $configurator
        ->services()
        ->set('hasura.metadata.remote_schema', RemoteSchema::class)
            ->args(
                [
                    abstract_arg('remote schema name')
                ]
            )
        ->set('hasura.metadata.remote_schema_reload_state_processor', RemoteSchemaReloadStateProcessor::class)
            ->args(
                [
                    service('hasura.metadata.remote_schema'),
                    service('hasura.api_client.client')
                ]
            )
            ->tag('hasura.metadata.state_processor')
        ->set('hasura.metadata.yaml_operator', YamlOperator::class)
            ->args(
                [
                    service('filesystem')
                ]
            )
        ->set('hasura.metadata.manager', Manager::class)
            ->args(
                [
                    service('hasura.api_client.client'),
                    param('hasura.metadata.path'),
                    service('hasura.metadata.yaml_operator')
                ]
            )
        ->set('hasura.metadata.command', BaseCommand::class)
            ->abstract()
            ->args(
                [
                    service('hasura.metadata.manager')
                ]
            )
        ->set('hasura.metadata.apply_command', ApplyMetadata::class)
            ->parent('hasura.metadata.command')
            ->tag('console.command', ['command' => 'hasura:metadata:apply'])
        ->set('hasura.metadata.clear_command', ClearMetadata::class)
            ->parent('hasura.metadata.command')
            ->tag('console.command', ['command' => 'hasura:metadata:clear'])
        ->set('hasura.metadata.drop_inconsistent_command', DropInconsistentMetadata::class)
            ->parent('hasura.metadata.command')
            ->tag('console.command', ['command' => 'hasura:metadata:drop-inconsistent'])
        ->set('hasura.metadata.export_command', ExportMetadata::class)
            ->parent('hasura.metadata.command')
            ->tag('console.command', ['command' => 'hasura:metadata:export'])
        ->set('hasura.metadata.get_inconsistent_command', GetInconsistentMetadata::class)
            ->parent('hasura.metadata.command')
            ->tag('console.command', ['command' => 'hasura:metadata:get-inconsistent'])
        ->set('hasura.metadata.reload_command', ReloadMetadata::class)
            ->parent('hasura.metadata.command')
            ->tag('console.command', ['command' => 'hasura:metadata:reload'])
        ->set('hasura.metadata.persist_state_command', PersistState::class)
            ->args(
                [
                    tagged_iterator('hasura.metadata.state_processor')
                ]
            )
            ->tag('console.command', ['command' => 'hasura:metadata:persist-state'])
    ;
};