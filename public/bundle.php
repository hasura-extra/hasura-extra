<?php

require __DIR__ . '/../vendor/autoload.php';

use Hasura\Bundle\Tests\TestKernel;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;

new class {
    public function __construct()
    {
        (new Dotenv)->bootEnv(__DIR__.'/.env');

        $kernel = new TestKernel('test', 1);
        $request = Request::createFromGlobals();
        $response = $kernel->handle($request);
        $response->send();
        $kernel->terminate($request, $response);
    }
};