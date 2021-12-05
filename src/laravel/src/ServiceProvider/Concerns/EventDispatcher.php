<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\ServiceProvider\Concerns;

use Hasura\EventDispatcher\TableEventRequestHandler;
use Hasura\Laravel\EventDispatcher\Psr14EventDispatcher;
use Hasura\Laravel\ServiceProvider\HasuraServiceProvider;
use Nyholm\Psr7\Factory\Psr17Factory;

/**
 * @mixin HasuraServiceProvider
 */
trait EventDispatcher
{
    private function registerEventDispatcher(): void
    {
        $this->app->singleton(Psr14EventDispatcher::class);

        $this->app->singleton(
            TableEventRequestHandler::class,
            static fn ($app) => new TableEventRequestHandler(
                $app[Psr14EventDispatcher::class],
                new Psr17Factory()
            )
        );
    }
}
