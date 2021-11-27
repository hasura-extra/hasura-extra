<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel;

use Hasura\ApiClient\Client;
use Hasura\Laravel\AuthHook\AccessRoleDecider;
use Hasura\Laravel\EventDispatcher\Psr14EventDispatcher;
use Illuminate\Support\ServiceProvider;

final class HasuraServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes(
                [
                    $this->getConfigFile() => config_path('hasura.php')
                ],
                'hasura-config'
            );
        }
    }

    public function register(): void
    {
        $this->mergeConfigFrom($this->getConfigFile(), 'hasura');

        $this->registerApiClient();
        $this->registerAuthHook();
        $this->registerEventDispatcher();
    }

    private function registerApiClient(): void
    {
        $this->app->singleton(
            'hasura.api_client.client',
            fn() => new Client(
                config('hasura.base_uri'),
                config('hasura.admin_secret')
            )
        );
    }

    private function registerAuthHook(): void
    {
        $this->app->singleton(
            'hasura.auth_hook.access_role_decider',
            fn($app) => new AccessRoleDecider(
                config('hasura.auth.anonymous_role'),
                config('hasura.auth.default_role'),
                $app['auth']->guard(config('hasura.auth.guard'))
            )
        );
    }

    private function registerEventDispatcher(): void
    {
        $this->app->singleton(
            'hasura.event_dispatcher.psr14_event_dispatcher',
            fn($app) => new Psr14EventDispatcher($app['events'])
        );
    }

    private function getConfigFile(): string
    {
        return __DIR__ . '/../config/hasura.php';
    }
}