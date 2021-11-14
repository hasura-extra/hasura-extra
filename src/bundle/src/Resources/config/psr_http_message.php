<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Hasura\Bundle\Controller\Psr15RequestHandler;
use Nyholm\Psr7\Factory\Psr17Factory;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;

return static function (ContainerConfigurator $configurator) {
    $configurator
        ->services()
        ->set('hasura.psr_http_message.psr17_factory', Psr17Factory::class)

        ->set('hasura.psr_http_message.psr_http_factory', PsrHttpFactory::class)
            ->args(
                [
                    service('hasura.psr_http_message.psr17_factory'),
                    service('hasura.psr_http_message.psr17_factory'),
                    service('hasura.psr_http_message.psr17_factory'),
                    service('hasura.psr_http_message.psr17_factory')
                ]
            )

        ->set('hasura.psr_http_message.http_foundation_factory', HttpFoundationFactory::class)

        ->set('hasura.psr_http_message.psr15_request_handler_controller', Psr15RequestHandler::class)
            ->public()
            ->abstract()
            ->args(
                [
                    abstract_arg('psr15 request handler'),
                    service('hasura.psr_http_message.psr_http_factory'),
                    service('hasura.psr_http_message.http_foundation_factory')
                ]
            )
    ;
};