<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Hasura\Metadata\Command\ReloadMetadata;
use Hasura\Metadata\Manager;
use Hasura\Metadata\YamlOperator;

return static function (ContainerConfigurator $configurator) {
    $configurator
        ->services()
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
        ->set('hasura.metadata.reload_command', ReloadMetadata::class)
            ->args(
                [
                    service('hasura.metadata.manager')
                ]
            )
    ;
};