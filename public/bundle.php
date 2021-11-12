<?php

require __DIR__ . '/../vendor/autoload.php';

use Hasura\Bundle\Tests\TestKernel;
use Symfony\Component\HttpFoundation\Request;

new class {
    public function __construct()
    {
        $kernel = new TestKernel('test', 1);
        $request = Request::createFromGlobals();
        $response = $kernel->handle($request);
        $response->send();
        $kernel->terminate($request, $response);
    }
};