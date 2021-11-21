<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Hasura\AuthHook\ChainSessionVariableEnhancer;
use Hasura\AuthHook\RequestHandler;
use Hasura\Bundle\Auth\RoleAnonymousVoter;
use Hasura\Bundle\AuthHook\AccessRoleDecider;
use Hasura\Bundle\Controller\Psr15RequestHandler;

return static function (ContainerConfigurator $configurator) {
    $configurator
        ->services()
        ->set('hasura.auth_hook.access_role_decider', AccessRoleDecider::class)
            ->args(
                [
                    param('hasura.auth.anonymous_role'),
                    param('hasura.auth.default_role'),
                    service('security.token_storage')->nullOnInvalid(),
                    service('security.authorization_checker')->nullOnInvalid()
                ]
            )

        ->set('hasura.auth_hook.session_variable_enhancer', ChainSessionVariableEnhancer::class)
            ->args(
                [
                    tagged_iterator('hasura.auth_hook.session_variable_enhancer')
                ]
            )

        ->set('hasura.auth_hook.request_handler', RequestHandler::class)
            ->args(
                [
                    service('hasura.auth_hook.access_role_decider'),
                    service('hasura.psr_http_message.psr17_factory'),
                    service('hasura.psr_http_message.psr17_factory'),
                    service('hasura.auth_hook.session_variable_enhancer')
                ]
            )

        ->set('hasura.auth_hook.controller', Psr15RequestHandler::class)
            ->parent('hasura.psr_http_message.psr15_request_handler_controller')
            ->arg('index_0', service('hasura.auth_hook.request_handler'))

        ->set('hasura.auth.role_anonymous_voter', RoleAnonymousVoter::class)
            ->args(
                [
                    param('hasura.auth.anonymous_role')
                ]
            )
            ->tag('security.voter')
    ;
};