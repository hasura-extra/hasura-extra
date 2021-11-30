<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\ServiceProvider\Concerns;

use GraphQL\Type\Schema;
use Hasura\GraphQLiteBridge\Controller\DummyQuery;
use Hasura\GraphQLiteBridge\Field\AnnotationTracker;
use Hasura\GraphQLiteBridge\Field\AnnotationTrackingMiddleware as FieldAnnotationTrackingMiddleware;
use Hasura\GraphQLiteBridge\Field\ArgNamingMiddleware as FieldArgNamingMiddleware;
use Hasura\GraphQLiteBridge\Field\AuthorizationMiddleware as FieldAuthorizationMiddleware;
use Hasura\GraphQLiteBridge\Parameter\ArgNamingMiddleware as ParameterArgNamingMiddleware;
use Hasura\GraphQLiteBridge\Parameter\AvoidExplicitDefaultNullMiddleware as ParameterAvoidExplicitDefaultNullMiddleware;
use Hasura\GraphQLiteBridge\RemoteSchemaPermissionStateProcessor;
use Hasura\GraphQLiteBridge\RootTypeMapperFactory;
use Hasura\Laravel\GraphQLite\AuthorizationService;
use Hasura\Laravel\GraphQLite\Parameter\ArgModelMiddleware as ParameterArgModelMiddleware;
use Hasura\Laravel\ServiceProvider\HasuraServiceProvider;
use Hasura\Metadata\RemoteSchemaInterface;
use TheCodingMachine\GraphQLite\AggregateControllerQueryProviderFactory;
use TheCodingMachine\GraphQLite\Laravel\SanePsr11ContainerAdapter;
use TheCodingMachine\GraphQLite\SchemaFactory;
use TheCodingMachine\GraphQLite\Security\AuthorizationServiceInterface;

/**
 * @mixin HasuraServiceProvider
 */
trait GraphQLite
{
    private function registerGraphQLite(): void
    {
        $this->app->singleton(AuthorizationService::class);
        $this->app->bind(AuthorizationServiceInterface::class, AuthorizationService::class);

        $this->app->singleton(AnnotationTracker::class);

        $this->app->singleton(FieldAnnotationTrackingMiddleware::class);

        $this->app->singleton(
            FieldAuthorizationMiddleware::class,
            fn($app) => new FieldAuthorizationMiddleware($app[AuthorizationService::class])
        );

        $this->app->singleton(FieldArgNamingMiddleware::class);

        $this->app->singleton(ParameterArgNamingMiddleware::class);

        $this->app->singleton(ParameterArgModelMiddleware::class);

        $this->app->singleton(ParameterAvoidExplicitDefaultNullMiddleware::class);

        $this->app->singleton(
            RemoteSchemaPermissionStateProcessor::class,
            static function ($app) {
                if (!config('hasura.remote_schema_name')) {
                    throw new \LogicException(
                        sprintf(
                            '`hasura.remote_schema_name` should be set when you want to use %s',
                            RemoteSchemaPermissionStateProcessor::class
                        )
                    );
                }

                return new RemoteSchemaPermissionStateProcessor(
                    $app[RemoteSchemaInterface::class],
                    $app[Schema::class],
                    $app[AnnotationTracker::class]
                );
            }
        );

        $this->app->singleton(RootTypeMapperFactory::class);

        $this->app->extend(SchemaFactory::class, function (SchemaFactory $factory, $app) {
            $factory->addQueryProviderFactory(
                new AggregateControllerQueryProviderFactory(
                    [DummyQuery::class],
                    new SanePsr11ContainerAdapter($app)
                )
            );

            $factory->addFieldMiddleware($app[FieldAnnotationTrackingMiddleware::class]);
            $factory->addFieldMiddleware($app[FieldArgNamingMiddleware::class]);
            $factory->addFieldMiddleware($app[FieldAuthorizationMiddleware::class]);

            $factory->addParameterMiddleware($app[ParameterArgNamingMiddleware::class]);
            $factory->addParameterMiddleware($app[ParameterArgModelMiddleware::class]);
            $factory->addParameterMiddleware($app[ParameterAvoidExplicitDefaultNullMiddleware::class]);

            $factory->addRootTypeMapperFactory($app[RootTypeMapperFactory::class]);

            $factory->setAuthorizationService($app[AuthorizationService::class]);

            return $factory;
        });
    }
}