<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\Tests\Integration\ServiceProvider;

use Hasura\AuthHook\RequestHandler;
use Hasura\AuthHook\SessionVariableEnhancerInterface;
use Hasura\EventDispatcher\TableEventRequestHandler;
use Hasura\GraphQLiteBridge\Field\AnnotationTrackingMiddleware as FieldAnnotationTrackingMiddleware;
use Hasura\GraphQLiteBridge\Field\ArgNamingMiddleware as FieldArgNamingMiddleware;
use Hasura\GraphQLiteBridge\Field\AuthorizationMiddleware as FieldAuthorizationMiddleware;
use Hasura\GraphQLiteBridge\Parameter\ArgNamingMiddleware as ParameterArgNamingMiddleware;
use Hasura\GraphQLiteBridge\Parameter\AvoidExplicitDefaultNullMiddleware as ParameterAvoidExplicitDefaultNullMiddleware;
use Hasura\GraphQLiteBridge\RootTypeMapperFactory;
use Hasura\Laravel\Auth\GateRoleChecker;
use Hasura\Laravel\Auth\InheritanceRole;
use Hasura\Laravel\EventDispatcher\Psr14EventDispatcher;
use Hasura\Laravel\GraphQLite\AuthorizationService;
use Hasura\Laravel\Tests\TestCase;
use Hasura\Metadata\Command\ApplyMetadata;
use Hasura\Metadata\Command\ClearMetadata;
use Hasura\Metadata\Command\DropInconsistentMetadata;
use Hasura\Metadata\Command\ExportMetadata;
use Hasura\Metadata\Command\GetInconsistentMetadata;
use Hasura\Metadata\Command\PersistState;
use Hasura\Metadata\Command\ReloadMetadata;
use Hasura\Metadata\ManagerInterface;
use Hasura\Metadata\StateProcessorInterface;
use Hasura\SailorBridge\Command\Codegen;
use Hasura\SailorBridge\Command\Introspect;
use Hasura\SailorBridge\EndpointConfig;
use Hasura\SailorBridge\SailorClient;
use Illuminate\Auth\RequestGuard;
use Illuminate\Contracts\Console\Kernel as ConsoleKernelContract;
use Spawnia\Sailor\Configuration;

final class HasuraServiceProviderTest extends TestCase
{
    public function testBooted(): void
    {
        $commands = collect(
            $this->app[ConsoleKernelContract::class]->all()
        )->map(
            fn(object $command) => $command::class
        )->toArray();

        $this->assertContains(ApplyMetadata::class, $commands);
        $this->assertContains(ClearMetadata::class, $commands);
        $this->assertContains(DropInconsistentMetadata::class, $commands);
        $this->assertContains(ExportMetadata::class, $commands);
        $this->assertContains(GetInconsistentMetadata::class, $commands);
        $this->assertContains(PersistState::class, $commands);
        $this->assertContains(ReloadMetadata::class, $commands);
        $this->assertContains(Codegen::class, $commands);
        $this->assertContains(Introspect::class, $commands);

        $this->assertInstanceOf(RequestGuard::class, $this->app['auth']->guard('hasura'));
        $this->assertInstanceOf(EndpointConfig::class, Configuration::endpoint('hasura'));
    }

    public function testServicesRegistered(): void
    {
        $this->assertTrue($this->app->has(GateRoleChecker::class));
        $this->assertTrue($this->app->has(InheritanceRole::class));
        $this->assertTrue($this->app->has(SessionVariableEnhancerInterface::class));
        $this->assertTrue($this->app->has(RequestHandler::class));

        $this->assertTrue($this->app->has(Psr14EventDispatcher::class));
        $this->assertTrue($this->app->has(TableEventRequestHandler::class));

        $this->assertTrue($this->app->has(ManagerInterface::class));
        $this->assertTrue($this->app->has(StateProcessorInterface::class));
        $this->assertTrue($this->app->has(ApplyMetadata::class));
        $this->assertTrue($this->app->has(ClearMetadata::class));
        $this->assertTrue($this->app->has(DropInconsistentMetadata::class));
        $this->assertTrue($this->app->has(ExportMetadata::class));
        $this->assertTrue($this->app->has(GetInconsistentMetadata::class));
        $this->assertTrue($this->app->has(PersistState::class));
        $this->assertTrue($this->app->has(ReloadMetadata::class));

        $this->assertTrue($this->app->has(AuthorizationService::class));
        $this->assertTrue($this->app->has(FieldArgNamingMiddleware::class));
        $this->assertTrue($this->app->has(FieldAuthorizationMiddleware::class));
        $this->assertTrue($this->app->has(FieldAnnotationTrackingMiddleware::class));
        $this->assertTrue($this->app->has(ParameterArgNamingMiddleware::class));
        $this->assertTrue($this->app->has(ParameterAvoidExplicitDefaultNullMiddleware::class));
        $this->assertTrue($this->app->has(RootTypeMapperFactory::class));

        $this->assertTrue($this->app->has(SailorClient::class));
        $this->assertTrue($this->app->has(EndpointConfig::class));
        $this->assertTrue($this->app->has(Codegen::class));
        $this->assertTrue($this->app->has(Introspect::class));
    }
}