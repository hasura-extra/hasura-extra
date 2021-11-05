<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge\Tests;

use Hasura\GraphQLiteBridge\Field\AnnotationTracker;
use Hasura\GraphQLiteBridge\Field\AnnotationTrackingMiddleware;
use Hasura\GraphQLiteBridge\Field\AuthorizationMiddleware;
use Hasura\GraphQLiteBridge\RootTypeMapperFactory;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Psr16Cache;
use TheCodingMachine\GraphQLite\Containers\BasicAutoWiringContainer;
use TheCodingMachine\GraphQLite\Containers\EmptyContainer;
use TheCodingMachine\GraphQLite\SchemaFactory;
use TheCodingMachine\GraphQLite\Security\AuthorizationServiceInterface;
use TheCodingMachine\GraphQLite\Security\VoidAuthenticationService;

trait SchemaFactoryTrait
{
    protected AnnotationTracker $annotationTracker;

    protected SchemaFactory $schemaFactory;

    private function initSchemaFactory(): void
    {
        $annotationTracker = new AnnotationTracker();
        $container = new BasicAutoWiringContainer(new EmptyContainer());
        $cache = new Psr16Cache(new ArrayAdapter());
        $authorizationService = $this->createAuthorizationService();

        $factory = new SchemaFactory($cache, $container);
        $factory->setAuthenticationService(new VoidAuthenticationService());
        $factory->setAuthorizationService($authorizationService);

        $factory->addControllerNamespace('Hasura\GraphQLiteBridge\Tests\Fixture');
        $factory->addControllerNamespace('Hasura\GraphQLiteBridge\Controller');

        $factory->addFieldMiddleware(new AnnotationTrackingMiddleware($annotationTracker));
        $factory->addFieldMiddleware(new AuthorizationMiddleware($authorizationService));

        $factory->addRootTypeMapperFactory(new RootTypeMapperFactory());

        $factory->addTypeNamespace('Hasura\GraphQLiteBridge\Tests\Fixture');

        $factory->devMode();

        $this->annotationTracker = $annotationTracker;
        $this->schemaFactory = $factory;
    }

    private function createAuthorizationService(): AuthorizationServiceInterface
    {
        return new class implements AuthorizationServiceInterface {

            public function isAllowed(string $right, $subject = null): bool
            {
                return 'allow' === $right;
            }
        };
    }
}