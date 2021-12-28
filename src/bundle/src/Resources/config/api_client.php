<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Hasura\ApiClient\Client;

return static function (ContainerConfigurator $configurator) {
    $configurator
        ->services()
        ->alias('hasura.api_client.http_client', 'http_client')

        ->set('hasura.api_client.client', Client::class)
            ->args(
                [
                    param('hasura.base_uri'),
                    abstract_arg('hasura admin secret'),
                    [],
                    service('hasura.api_client.http_client')->nullOnInvalid()
                ]
            )

        ->alias(Client::class, 'hasura.api_client.client')
    ;
};