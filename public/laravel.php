<?php

require __DIR__ . '/../vendor/autoload.php';

use Hasura\Laravel\Tests\Fixture\App\Http\Kernel;
use Illuminate\Http\Request;

new class {
    public function __construct()
    {
        $_SERVER += [
            'DATABASE_URL' => 'postgres://hasura:hasura@localhost:5432/bundle',
            'HASURA_BASE_URI' => 'http://hasura:8085'
        ];
        $app = require_once __DIR__.'/../src/laravel/tests/Fixture/bootstrap/app.php';

        $kernel = $app->make(Kernel::class);

        $response = $kernel->handle(
            $request = Request::capture()
        )->send();

        $kernel->terminate($request, $response);
    }
};