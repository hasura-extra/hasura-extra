<?php

require __DIR__ . '/../vendor/autoload.php';

use GraphQL\Error\DebugFlag;
use GraphQL\Server\StandardServer;
use Hasura\GraphQLiteBridge\Tests\SchemaFactoryTrait;
use TheCodingMachine\GraphQLite\Context\Context;

new class {

    use SchemaFactoryTrait;

    public function __construct()
    {
        $this->initSchemaFactory();

        $standardServer = new StandardServer(
            [
                'schema' => $this->schemaFactory->createSchema(),
                'context' => new Context(),
                'debugFlag' => DebugFlag::INCLUDE_DEBUG_MESSAGE | DebugFlag::INCLUDE_TRACE
            ]
        );

        $standardServer->handleRequest(exitWhenDone: true);
    }
};