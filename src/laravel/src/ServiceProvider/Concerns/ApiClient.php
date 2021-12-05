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

/**
 * @mixin HasuraServiceProvider
 */
trait ApiClient
{
    private function registerApiClient(): void
    {
        $this->app->singleton(
            Client::class,
            static fn () => new Client(
                config('hasura.base_uri'),
                config('hasura.admin_secret')
            )
        );
    }
}
