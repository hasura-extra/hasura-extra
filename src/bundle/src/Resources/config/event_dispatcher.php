<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Hasura\Bundle\Controller\Psr15RequestHandler;
use Hasura\EventDispatcher\TableEventRequestHandler;

return static function (ContainerConfigurator $configurator) {
    $configurator
        ->services()
        ->set('hasura.event_dispatcher.table_event_request_handler', TableEventRequestHandler::class)
            ->args(
                [
                    service('event_dispatcher'),
                    service('hasura.psr_http_message.psr17_factory')
                ]
            )

        ->set('hasura.event_dispatcher.table_event_request_handler_controller', Psr15RequestHandler::class)
            ->parent('hasura.psr_http_message.psr15_request_handler_controller')
            ->arg('index_0', service('hasura.event_dispatcher.table_event_request_handler'))
    ;
};