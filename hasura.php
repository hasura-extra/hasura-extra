<?php

return [
    'baseUri' => 'http://localhost:8080',
    'adminSecret' => 'test',
    'metadata' => [
        'path' => __DIR__ . '/hasura/metadata'
    ],
    'sailor' => [
        'executorNamespace' => 'App\\GraphqlExecutor',
        'executorPath' => __DIR__ . '/hasura/graphql_executor',
        'querySpecPath' => __DIR__ . '/hasura/graphql',
        'schemaPath' => __DIR__ . '/hasura/schema.graphql'
    ]
];