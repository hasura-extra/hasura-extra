<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests\DependencyInjection;

use Hasura\Bundle\DependencyInjection\HasuraExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class HasuraExtensionTest extends TestCase
{
    public function testLoadWithRemoteSchema(): void
    {
        $container = new ContainerBuilder();
        $extension = new HasuraExtension();

        $extension->load(
            [
                [
                    'remote_schema_name' => 'bundle',
                ],
            ],
            $container
        );

        $this->assertTrue($container->has('hasura.metadata.remote_schema'));
    }

    public function testLoad(): void
    {
        $container = new ContainerBuilder();
        $extension = new HasuraExtension();

        $extension->load([], $container);

        $this->assertTrue($container->hasParameter('hasura.base_uri'));
        $this->assertTrue($container->hasParameter('hasura.metadata.path'));

        $this->assertFalse($container->has('hasura.metadata.remote_schema'));

        $this->assertTrue($container->has('hasura.auth_hook.controller'));
        $this->assertTrue($container->has('hasura.auth_hook.request_handler'));
        $this->assertTrue($container->has('hasura.auth_hook.session_variable_enhancer'));
        $this->assertTrue($container->has('hasura.auth_hook.access_role_decider'));

        $this->assertTrue($container->has('hasura.api_client.client'));

        $this->assertTrue($container->has('hasura.event_dispatcher.table_event_request_handler'));
        $this->assertTrue($container->has('hasura.event_dispatcher.table_event_request_handler_controller'));

        $this->assertTrue($container->has('hasura.metadata.manager'));
        $this->assertTrue($container->has('hasura.metadata.yaml_operator'));
        $this->assertTrue($container->has('hasura.metadata.apply_command'));
        $this->assertTrue($container->has('hasura.metadata.clear_command'));
        $this->assertTrue($container->has('hasura.metadata.drop_inconsistent_command'));
        $this->assertTrue($container->has('hasura.metadata.get_inconsistent_command'));
        $this->assertTrue($container->has('hasura.metadata.reload_command'));

        $this->assertTrue($container->has('hasura.psr_http_message.psr17_factory'));
        $this->assertTrue($container->has('hasura.psr_http_message.psr_http_factory'));
        $this->assertTrue($container->has('hasura.psr_http_message.http_foundation_factory'));
        $this->assertTrue($container->has('hasura.psr_http_message.psr15_request_handler_controller'));
    }
}
