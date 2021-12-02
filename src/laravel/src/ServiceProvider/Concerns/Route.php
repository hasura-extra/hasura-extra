<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\ServiceProvider\Concerns;

use Hasura\Laravel\ServiceProvider\HasuraServiceProvider;

/**
 * @mixin HasuraServiceProvider
 */
trait Route
{
    private function bootRoute(): void
    {
        if (config('hasura.routes.auth_hook.enabled')) {
            $this->loadRoutesFrom(__DIR__ . '/../../../routes/auth_hook.php');
        }

        if (config('hasura.routes.table_event.enabled')) {
            $this->loadRoutesFrom(__DIR__ . '/../../../routes/table_event.php');
        }
    }
}