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
                ['remote_schema_name' => 'bundle']
            ],
            $container
        );

        $this->assertTrue($container->has('hasura.metadata.remote_schema'));
        $this->assertTrue($container->has('hasura.graphql.remote_schema_permission_state_processor'));
    }

    public function testLoad(): void
    {
        $container = new ContainerBuilder();
        $extension = new HasuraExtension();

        $extension->load([], $container);

        $this->assertTrue($container->hasParameter('hasura.base_uri'));
        $this->assertTrue($container->hasParameter('hasura.maker.decorate_make_entity'));
        $this->assertTrue($container->hasParameter('hasura.metadata.path'));
        $this->assertTrue($container->hasParameter('hasura.metadata.state_processors.enabled_inherited_roles'));
        $this->assertTrue($container->hasParameter('hasura.metadata.state_processors.enabled_remote_schema_permissions'));
        $this->assertTrue($container->hasParameter('hasura.sailor.executor_namespace'));
        $this->assertTrue($container->hasParameter('hasura.sailor.query_spec_path'));
        $this->assertTrue($container->hasParameter('hasura.sailor.executor_path'));
        $this->assertTrue($container->hasParameter('hasura.sailor.schema_path'));

        $this->assertFalse($container->has('hasura.metadata.remote_schema'));
        $this->assertFalse($container->has('hasura.graphql.remote_schema_permission_state_processor'));

        $this->assertTrue($container->has('hasura.auth_hook.controller'));
        $this->assertTrue($container->has('hasura.auth_hook.request_handler'));
        $this->assertTrue($container->has('hasura.auth_hook.session_variable_enhancer'));
        $this->assertTrue($container->has('hasura.auth_hook.access_role_decider'));

        $this->assertTrue($container->has('hasura.api_client.client'));

        $this->assertTrue($container->has('hasura.event_dispatcher.table_event_request_handler'));
        $this->assertTrue($container->has('hasura.event_dispatcher.table_event_request_handler_controller'));

        $this->assertTrue($container->has('hasura.graphql.aggregate_query_provider_factory'));
        $this->assertTrue($container->has('hasura.graphql.object_assertion.executor'));
        $this->assertTrue($container->has('hasura.graphql.parameter.assertion_middleware'));
        $this->assertTrue($container->has('hasura.graphql.parameter.arg_entity_middleware'));
        $this->assertTrue($container->has('hasura.graphql.parameter.arg_naming_middleware'));
        $this->assertTrue($container->has('hasura.graphql.parameter.avoid_explicit_default_null_middleware'));
        $this->assertTrue($container->has('hasura.graphql.parameter.object_assertion_middleware'));
        $this->assertTrue($container->has('hasura.graphql.field.transactional_middleware'));
        $this->assertTrue($container->has('hasura.graphql.field.authorization_middleware'));
        $this->assertTrue($container->has('hasura.graphql.field.annotation_tracker'));
        $this->assertTrue($container->has('hasura.graphql.field.annotation_tracking_middleware'));
        $this->assertTrue($container->has('hasura.graphql.field.arg_naming_middleware'));
        $this->assertTrue($container->has('hasura.graphql.field.object_assertion_middleware'));
        $this->assertTrue($container->has('hasura.graphql.root_type_mapper_factory'));
        $this->assertTrue($container->has('hasura.graphql.authorization_service'));
        $this->assertTrue($container->has('hasura.graphql.controller.dummy_query'));

        $this->assertTrue($container->has('hasura.maker.make_entity'));

        $this->assertTrue($container->has('hasura.metadata.manager'));
        $this->assertTrue($container->has('hasura.metadata.yaml_operator'));
        $this->assertTrue($container->has('hasura.metadata.apply_command'));
        $this->assertTrue($container->has('hasura.metadata.clear_command'));
        $this->assertTrue($container->has('hasura.metadata.drop_inconsistent_command'));
        $this->assertTrue($container->has('hasura.metadata.get_inconsistent_command'));
        $this->assertTrue($container->has('hasura.metadata.reload_command'));
        $this->assertTrue($container->has('hasura.metadata.persist_state_command'));
        $this->assertTrue($container->has('hasura.metadata.state_processor'));
        $this->assertTrue($container->has('hasura.metadata.reload_state_processor'));
        $this->assertTrue($container->has('hasura.metadata.inherited_roles_state_processor'));

        $this->assertTrue($container->has('hasura.psr_http_message.psr17_factory'));
        $this->assertTrue($container->has('hasura.psr_http_message.psr_http_factory'));
        $this->assertTrue($container->has('hasura.psr_http_message.http_foundation_factory'));
        $this->assertTrue($container->has('hasura.psr_http_message.psr15_request_handler_controller'));

        $this->assertTrue($container->has('hasura.sailor.client'));
        $this->assertTrue($container->has('hasura.sailor.codegen_command'));
        $this->assertTrue($container->has('hasura.sailor.endpoint_config'));
        $this->assertTrue($container->has('hasura.sailor.introspect_command'));
    }

    public function testPrependConfiguration(): void
    {
        $container = new ContainerBuilder();
        $extension = new HasuraExtension();

        $extension->prepend($container);

        $this->assertSame(
            [
                [
                    'enable_authenticator_manager' => true
                ]
            ],
            $container->getExtensionConfig('security')
        );
    }

    public function testDisableDecorateMakeEntity(): void
    {
        $container = new ContainerBuilder();
        $extension = new HasuraExtension();

        $extension->load([['decorate_make_entity' => false]], $container);

        $this->assertFalse($container->getParameter('hasura.maker.decorate_make_entity'));
    }

    public function testDisableMetadataStateProcessors(): void
    {
        $container = new ContainerBuilder();
        $extension = new HasuraExtension();

        $extension->load(
            [
                [
                    'metadata' => [
                        'state_processors' => [
                            'enabled_remote_schema_permissions' => false,
                            'enabled_inherited_roles' => false,
                        ]
                    ]
                ]
            ],
            $container
        );

        $this->assertFalse($container->has('hasura.metadata.inherited_roles_state_processor'));
        $this->assertFalse($container->has('hasura.graphql.remote_schema_permission_state_processor'));
    }
}