<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\ServiceProvider\Concerns;

use Hasura\ApiClient\Client;
use Hasura\Laravel\ServiceProvider\HasuraServiceProvider;
use Hasura\SailorBridge\EndpointConfig;
use Hasura\SailorBridge\SailorClient;
use Spawnia\Sailor\Configuration;

/**
 * @mixin HasuraServiceProvider
 */
trait Sailor
{
    private function bootSailor(): void
    {
        Configuration::setEndpoint('hasura', $this->app[EndpointConfig::class]);
    }

    private function registerSailor(): void
    {
        $this->app->singleton(
            SailorClient::class,
            static fn($app) => new SailorClient($app[Client::class])
        );

        $this->app->singleton(
            EndpointConfig::class,
            static fn($app) => new EndpointConfig(
                $app[SailorClient::class],
                config('hasura.sailor.executor_namespace'),
                config('hasura.sailor.executor_path'),
                config('hasura.sailor.query_spec_path'),
                config('hasura.sailor.schema_path')
            )
        );
    }
}