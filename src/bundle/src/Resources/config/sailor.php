<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Hasura\SailorBridge\Command\Codegen;
use Hasura\SailorBridge\Command\Introspect;
use Hasura\SailorBridge\EndpointConfig;
use Hasura\SailorBridge\SailorClient;

return static function (ContainerConfigurator $configurator) {
    $configurator
        ->services()
        ->set('hasura.sailor.client', SailorClient::class)
            ->args(
                [
                    service('hasura.api_client.client')
                ]
            )

        ->set('hasura.sailor.endpoint_config', EndpointConfig::class)
            ->public()
            ->args(
                [
                    service('hasura.sailor.client'),
                    param('hasura.sailor.executor_namespace'),
                    param('hasura.sailor.executor_path'),
                    param('hasura.sailor.query_spec_path'),
                    param('hasura.sailor.schema_path')
                ]
            )

        ->set('hasura.sailor.introspect_command', Introspect::class)
            ->tag('console.command', ['command' => 'hasura:sailor:introspect'])

        ->set('hasura.sailor.codegen_command', Codegen::class)
            ->tag('console.command', ['command' => 'hasura:sailor:codegen'])
    ;
};