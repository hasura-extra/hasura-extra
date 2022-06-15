<?php

require __DIR__ . '/../vendor/autoload.php';

use Hasura\Bundle\Tests\TestKernel;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;

new class {
    public function __construct()
    {
        $_SERVER += [
            'DATABASE_URL' => 'postgres://hasura:hasura@localhost:5432/bundle',
            'HASURA_BASE_URI' => 'http://hasura:8085'
        ];
        $kernel = new TestKernel('test', 1);
        $request = Request::createFromGlobals();
        $response = $kernel->handle($request);
        $response->send();
        $kernel->terminate($request, $response);
    }
};