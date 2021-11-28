<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\Tests;

use Hasura\Laravel\ServiceProvider\HasuraServiceProvider;
use Hasura\Laravel\Tests\Fixture\App\Http\Kernel as HttpKernel;
use Illuminate\Contracts\Http\Kernel as HttpKernelContract;
use Illuminate\Testing\TestResponse;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use TheCodingMachine\GraphQLite\Laravel\Providers\GraphQLiteServiceProvider;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            GraphQLiteServiceProvider::class,
            HasuraServiceProvider::class
        ];
    }

    protected function resolveApplicationHttpKernel($app)
    {
        $app->singleton(HttpKernelContract::class, HttpKernel::class);
    }

    protected function getBasePath()
    {
        return __DIR__ . '/Fixture';
    }

    protected function graphql(string $query, array $variables = null): TestResponse
    {
        return $this->postJson('/graphql', array_filter(['query' => $query, 'variables' => $variables]));
    }
}