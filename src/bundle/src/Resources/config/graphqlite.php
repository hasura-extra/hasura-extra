<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Hasura\Bundle\GraphQLite\AuthorizationService;
use Hasura\Bundle\GraphQLite\Field\ObjectAssertionMiddleware as FieldObjectAssertionMiddleware;
use Hasura\Bundle\GraphQLite\Field\TransactionalMiddleware;
use Hasura\Bundle\GraphQLite\ObjectAssertion\Executor;
use Hasura\Bundle\GraphQLite\Parameter\ArgEntityMiddleware;
use Hasura\Bundle\GraphQLite\Parameter\AssertionMiddleware;
use Hasura\Bundle\GraphQLite\Parameter\ObjectAssertionMiddleware as ParameterObjectAssertionMiddleware;
use Hasura\GraphQLiteBridge\Controller\DummyQuery;
use Hasura\GraphQLiteBridge\Field\AnnotationTracker;
use Hasura\GraphQLiteBridge\Field\AnnotationTrackingMiddleware;
use Hasura\GraphQLiteBridge\Field\ArgNamingMiddleware as FieldArgNamingMiddleware;
use Hasura\GraphQLiteBridge\Field\AuthorizationMiddleware;
use Hasura\GraphQLiteBridge\Parameter\ArgNamingMiddleware as ParameterArgNamingMiddleware;
use Hasura\GraphQLiteBridge\Parameter\AvoidExplicitDefaultNullMiddleware;
use Hasura\GraphQLiteBridge\RemoteSchemaPermissionStateProcessor;
use Hasura\GraphQLiteBridge\RootTypeMapperFactory;
use TheCodingMachine\GraphQLite\Schema;

return static function (ContainerConfigurator $configurator) {
    $configurator
        ->services()
        ->set('hasura.graphql.controller.dummy_query', DummyQuery::class)
        ->alias(DummyQuery::class, 'hasura.graphql.controller.dummy_query')
        ->set('hasura.graphql.authorization_service', AuthorizationService::class)
            ->args(
                [
                    service('security.authorization_checker')
                ]
            )
        ->set('hasura.graphql.field.authorization_middleware', AuthorizationMiddleware::class)
            ->args(
                [
                    service('hasura.graphql.authorization_service')
                ]
            )
        ->set('hasura.graphql.field.annotation_tracker', AnnotationTracker::class)
        ->set('hasura.graphql.field.annotation_tracking_middleware', AnnotationTrackingMiddleware::class)
            ->args(
                [
                    service('hasura.graphql.field.annotation_tracker')
                ]
            )
        ->set('hasura.graphql.field.arg_naming_middleware', FieldArgNamingMiddleware::class)
        ->set('hasura.graphql.field.transactional_middleware', TransactionalMiddleware::class)
            ->args(
                [
                    service('doctrine')
                ]
            )
        ->set('hasura.graphql.object_assertion.executor', Executor::class)
            ->args(
                [
                    service('validator'),
                    service('service_container')
                ]
            )
        ->set('hasura.graphql.field.object_assertion_middleware', FieldObjectAssertionMiddleware::class)
            ->args(
                [
                    service('hasura.graphql.object_assertion.executor')
                ]
            )
        ->set('hasura.graphql.parameter.assertion_middleware', AssertionMiddleware::class)
            ->args(
                [
                    service('validator.validator_factory'),
                    service('validator'),
                    service('translator')
                ]
            )
        ->set('hasura.graphql.parameter.object_assertion_middleware', ParameterObjectAssertionMiddleware::class)
            ->args(
                [
                    service('hasura.graphql.object_assertion.executor')
                ]
            )
        ->set('hasura.graphql.parameter.arg_naming_middleware', ParameterArgNamingMiddleware::class)
        ->set('hasura.graphql.parameter.avoid_explicit_default_null_middleware', AvoidExplicitDefaultNullMiddleware::class)
        ->set('hasura.graphql.parameter.arg_entity_middleware', ArgEntityMiddleware::class)
            ->args(
                [
                    service('doctrine')
                ]
            )
        ->set('hasura.graphql.root_type_mapper_factory', RootTypeMapperFactory::class)
            ->tag('graphql.root_type_mapper_factory')
        ->set('hasura.graphql.remote_schema_permission_state_processor', RemoteSchemaPermissionStateProcessor::class)
            ->args(
                [
                    service('hasura.metadata.remote_schema'),
                    service(Schema::class),
                    service('hasura.api_client.client'),
                    service('hasura.graphql.field.annotation_tracker')
                ]
            )
            ->tag('hasura.metadata.state_processor', ['priority' => 8])
    ;
};