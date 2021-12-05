<?php

declare(strict_types=1);

namespace Hasura\Laravel\Tests\Fixture\App\Http;

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

final class Kernel extends HttpKernel
{
    protected $middlewareGroups = [
        'api' => [],
    ];

    protected $routeMiddleware = [
        'auth' => Authenticate::class,
    ];
}
