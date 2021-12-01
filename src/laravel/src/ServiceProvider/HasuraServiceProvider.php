<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\ServiceProvider;

use Illuminate\Support\ServiceProvider;

final class HasuraServiceProvider extends ServiceProvider
{
    use Concerns\ApiClient;
    use Concerns\Auth;
    use Concerns\EventDispatcher;
    use Concerns\GraphQLite;
    use Concerns\Metadata;
    use Concerns\Routes;
    use Concerns\Sailor;

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

        $this->bootAuth();
        $this->bootMetadata();
        $this->bootRoutes();
        $this->bootSailor();
    }

    public function register(): void
    {
        $this->mergeConfigFrom($this->getConfigFile(), 'hasura');

        $this->registerApiClient();
        $this->registerAuth();
        $this->registerEventDispatcher();
        $this->registerGraphQLite();
        $this->registerMetadata();
        $this->registerSailor();
    }

    private function getConfigFile(): string
    {
        return __DIR__ . '/../../config/hasura.php';
    }
}