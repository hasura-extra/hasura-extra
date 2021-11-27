<?php

require __DIR__ . '/../vendor/autoload.php';

use GraphQL\Error\DebugFlag;
use GraphQL\Server\StandardServer;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;

new class {

    public function __construct()
    {
        $schema = new Schema(
            [
                'query' => new ObjectType(
                    [
                        'name' => 'Query',
                        'fields' => [
                            'dummy' => [
                                'type' => Type::nonNull(Type::string()),
                                'resolve' => fn() => 'dummy'
                            ]
                        ]
                    ]
                )
            ]
        );

        $standardServer = new StandardServer(
            [
                'schema' => $schema,
                'debugFlag' => DebugFlag::INCLUDE_DEBUG_MESSAGE | DebugFlag::INCLUDE_TRACE
            ]
        );

        $standardServer->handleRequest(exitWhenDone: true);
    }
};