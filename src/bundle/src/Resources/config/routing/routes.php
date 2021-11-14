<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Symfony\Component\Routing\Loader\Configurator;

return static function (RoutingConfigurator $configurator) {
    $configurator
        ->add('hasura.auth_hook.controller', '/hasura_auth_hook')
            ->controller('hasura.auth_hook.controller')
            ->methods(['GET'])

        ->add('hasura.event_dispatcher.table_event_request_handler_controller', '/hasura_table_event')
            ->controller('hasura.event_dispatcher.table_event_request_handler_controller')
            ->methods(['POST'])
    ;
};