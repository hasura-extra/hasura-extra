<?php

require __DIR__ . '/../vendor/autoload.php';

use Hasura\Laravel\Tests\Fixture\App\Http\Kernel;
use Illuminate\Http\Request;

new class {
    public function __construct()
    {
        $app = require_once __DIR__.'/../src/laravel/tests/Fixture/bootstrap/app.php';

        $kernel = $app->make(Kernel::class);

        $response = $kernel->handle(
            $request = Request::capture()
        )->send();

        $kernel->terminate($request, $response);
    }
};