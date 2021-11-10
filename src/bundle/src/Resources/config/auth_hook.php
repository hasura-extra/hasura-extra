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
use Hasura\Bundle\AuthHook\AccessRoleDecider;
use Hasura\Bundle\Controller\AuthHook;

return static function (ContainerConfigurator $configurator) {
    $configurator
        ->services()
        ->set('hasura.auth_hook.access_role_decider', AccessRoleDecider::class)
            ->args(
                [
                    param('hasura.auth_hook.anonymous_role'),
                    param('hasura.auth_hook.default_role'),
                    service('security.authorization_checker')
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
        ->set('hasura.auth_hook.controller', AuthHook::class)
            ->args(
                [
                    service('hasura.auth_hook.request_handler'),
                    service('hasura.psr_http_message.psr_http_factory'),
                    service('hasura.psr_http_message.http_foundation_factory')
                ]
            )
    ;
};